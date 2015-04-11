package cz.gdgscl.api.detector

import java.nio.file.Paths

import akka.actor.{Actor, ActorLogging, Props}
import cz.gdgscl.api.detector.DetectorService.{DetectNextCompany, DetectionResult, UpdateAvailableScripts}
import redis.clients.jedis.Jedis

import scala.concurrent.duration._
import scala.sys.process._
import scala.util.Try

object DetectorService {

  def props(redis: Jedis,
            redisBarcodeMap: String,
            redisCompanyMap: String,
            redisUnkBarcodeSet: String,
            scriptsDir: String) = Props(new DetectorService(redis,
    redisBarcodeMap, redisCompanyMap, redisUnkBarcodeSet, scriptsDir))

  case class DetectNextCompany()

  case class UpdateAvailableScripts()

  case class DetectionResult(company: String, error: Option[Boolean])

  val SCHEDULE_INTERVAL = 1 second
  val SCRIPT_REFRESH_INTERVAL = 1 minute

}


class DetectorService(redis: Jedis,
                      redisBarcodeMap: String,
                      redisCompanyMap: String,
                      redisUnkBarcodeSet: String,
                      scriptsDir: String) extends Actor with ActorLogging {

  import cz.gdgscl.api.json.JsonAPIProtocol._
  import spray.json._

  context.system.scheduler.scheduleOnce(
    DetectorService.SCHEDULE_INTERVAL,
    self, DetectNextCompany())

  context.system.scheduler.schedule(
    DetectorService.SCRIPT_REFRESH_INTERVAL,
    DetectorService.SCRIPT_REFRESH_INTERVAL,
    self, DetectNextCompany())

  override def receive = detect(listScriptsInDir())

  def detect(scripts: List[String]): Receive = {

    case UpdateAvailableScripts() =>
      context.become(detect(listScriptsInDir()))

    case DetectNextCompany() =>
      val code = redis.srandmember(redisUnkBarcodeSet)

      scripts.par.map(s => Try(s"$s $code" !!))
        .filter(_.isSuccess)
        .map(_.get)
        .map(_.toJson.convertTo[DetectionResult])
        .filter {
        _.error match {
          case None | Some(false) => true
          case _ => false
        }
      }.toList match {
        case Nil => log.info(s"No script returned non error result for barcode $code")
        case x: List =>
          x.map(comp => Option(redis.hget(redisCompanyMap, comp.company)))
            .filter(_.isDefined)
            .map(_.get.toBoolean) match {
            case Nil => log.debug(s"No result found for barcode $code")
            case x: List[Boolean] if x.forall(_ == true) || x.forall(_ == false) =>
              val dec = x.head
              log.debug(s"Decided for barcode $code - $x")
              redis.hset(redisBarcodeMap, code, x.toString())
              redis.srem(redisUnkBarcodeSet, code)
            case x: List[Boolean] =>
              log.debug(s"Cannot decide on value for $code - discarding - results ${x.mkString(",")}")
              redis.srem(redisUnkBarcodeSet, code)
          }
      }

      context.system.scheduler.scheduleOnce(
        DetectorService.SCHEDULE_INTERVAL,
        self, DetectNextCompany())
  }

  def listScriptsInDir() = {
    Paths.get(scriptsDir).toFile.listFiles().map(_.getAbsoluteFile.toString).toList
  }
}
