package cz.gdgscl.api

import akka.actor._
import akka.io.IO
import cz.gdgscl.api.APIBootstrap.Shutdown
import cz.gdgscl.api.WebAPIActor.Start
import cz.gdgscl.api.barcode.BarcodeService
import cz.gdgscl.api.company.CompanyService
import cz.gdgscl.api.detector.DetectorService
import redis.clients.jedis.{JedisPool, Jedis}
import scaldi.{Injectable, Injector}
import spray.can.Http


object APIBootstrap {

  def props(implicit inj: Injector) = Props(new APIBootstrap())

  sealed trait WebAPIDirectorProtocol

  case class Enable() extends WebAPIDirectorProtocol

  case class Disable() extends WebAPIDirectorProtocol

  case class Shutdown() extends WebAPIDirectorProtocol

}

class APIBootstrap(implicit val inj: Injector) extends Actor with ActorLogging with Injectable {

  import context._

  val webAPIActor = actorOf(WebAPIActor.props(
    inject[String](identified by "app.name")
  ), "http")

  val barcodeActor = actorOf(
    BarcodeService.props(inject[JedisPool].getResource,
      inject[String] (identified by "redisBarcodeMap"),
      inject[String] (identified by "redisBarcodeUnkSet")),
    "barcode")

  val companyActor = actorOf(
    CompanyService.props(inject[JedisPool].getResource,
      inject[String] (identified by "redisCompanyMap"),
      inject[String] (identified by "redisCompanyUnkSet")),
    "company")

  val detectorActor = actorOf(
    DetectorService.props(inject[JedisPool].getResource,
      inject[String] (identified by "redisBarcodeMap"),
      inject[String] (identified by "redisCompanyMap"),
      inject[String] (identified by "redisBarcodeUnkSet"),
      inject[String] (identified by "scriptsDir")),
    "detector")

  IO(Http)(context.system) ! Http.Bind(webAPIActor,
    interface = inject[String](identified by "http.host"),
    port = inject[Int](identified by "http.port"))

  webAPIActor ! Start()

  def receive: Receive = {
    case Shutdown() =>
      log.info(s"Shutting down system")
      webAPIActor ! Kill
  }

}



