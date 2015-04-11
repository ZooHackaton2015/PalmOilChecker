package cz.gdgscl.api.barcode

import akka.actor.{Actor, ActorLogging, ActorRef, Props}
import akka.pattern._
import cz.gdgscl.api.barcode.BarcodeService._
import cz.gdgscl.api.detector.DetectorService.{DetectCompany, DetectedCompany}
import cz.gdgscl.api.utils.DefaultTimeout
import redis.clients.jedis.Jedis

import scala.util.{Failure, Success, Try}

object BarcodeService {

  def props(redis: Jedis,
            redisBarcodeMap: String,
            redisCompanyMap: String,
            redisUnkCompanySet: String,
            detector: ActorRef) = Props(new BarcodeService(redis, redisBarcodeMap, redisCompanyMap, redisUnkCompanySet, detector))

  case class BarcodeInfo(palmOil: Boolean, company: Option[String] = None)

  case class GetBarcodeInfo(code: String)

  case class NoBarcodeInfo()

  case class NoCompany()

  case class CompanyInfo(palmOil : Boolean)

  case class GetCompanyInfo(name : String)

  case class SetBarcodeInfo(code: String, info: BarcodeInfo)

  case class BarcodeServiceERR(err: String)

  case class SetCompanyInfo(name : String,palmOil : Boolean)

  case class CompanyInfoSet()
}


class BarcodeService(
                      redis: Jedis,
                      redisBarcodeMap: String,
                      redisCompanyMap: String,
                      redisUnkCompanySet: String,
                      detector: ActorRef) extends Actor with ActorLogging with DefaultTimeout {

  import context.dispatcher

  override def receive: Receive = {

    case GetBarcodeInfo(code) =>
      val replyTo = sender()
      val hasPalmOil = redis.hget(redisBarcodeMap, code)
      Option(hasPalmOil.toBoolean) match {
        case Some(hasOil) => replyTo ! BarcodeInfo(hasOil)
        case None => detector ? DetectCompany(code) onComplete {
          case Success(DetectedCompany(None)) => replyTo ! NoBarcodeInfo()
          case Success(DetectedCompany(Some(comp))) =>
            Try(Option(redis.hget(redisCompanyMap, comp))) match {
              case Success(Some(hasOil)) =>
                redis.hset(redisBarcodeMap,code,hasOil)
                replyTo ! BarcodeInfo(hasOil.toBoolean)
              case Success(None) =>
                replyTo ! NoBarcodeInfo()
                redis.sadd(redisUnkCompanySet, comp)
              case Failure(t) => replyTo ! BarcodeServiceERR(t.getMessage)
            }
          case Failure(t) => replyTo ! BarcodeServiceERR(t.getMessage)
        }
      }

    case GetCompanyInfo(comp) =>
      val replyTo = sender()
      Option(redis.hget(redisCompanyMap,comp)) match {
        case Some(oilInfo) => replyTo ! CompanyInfo(oilInfo.toBoolean)
        case None => NoCompany()
      }

    case SetCompanyInfo(comp,palmOil : Boolean)  =>
      val pipe = redis.pipelined()
      pipe.hset(redisCompanyMap,comp,palmOil.toString)
      pipe.srem(redisUnkCompanySet,comp)
      pipe.sync()
      sender() ! CompanyInfoSet()
  }
}
