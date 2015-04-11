package cz.gdgscl.api.detector

import akka.actor.{Actor, ActorLogging, Props}
import cz.gdgscl.api.detector.DetectorService.{DetectCompany, DetectedCompany, DetectionResult}

import scala.concurrent.Future
import scala.sys.process._
import scala.util.Try

object DetectorService {

  def props(scripts: List[String]) = Props(new DetectorService(scripts))

  case class DetectCompany(barcode: String)
  case class DetectedCompany(company: Option[String])
  case class DetectionResult(company: String)
}


class DetectorService(scripts: List[String]) extends Actor with ActorLogging {

  import cz.gdgscl.api.json.JsonAPIProtocol._
  import spray.json._

  override def receive: Receive = {
    case DetectCompany(code) =>
      val replyTo = sender()
      Future {
        scripts.par.map(s => Try(s"$s $code" !!))
          .filter(_.isSuccess)
          .map(_.get)
          .map(_.toJson.convertTo[DetectionResult])
          .toList match {
          case Nil => DetectedCompany(None)
          case head :: tail => replyTo ! DetectedCompany(Some(head.company))
        }
      }
  }
}
