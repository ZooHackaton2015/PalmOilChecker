package cz.gdgscl.api

import akka.actor._
import cz.gdgscl.api.APIBootstrap.Shutdown
import cz.gdgscl.api.WebAPIActor.Start
import scaldi.{Injectable, Injector}


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

//  val barcodeActor = actorOf(
//    BarcodeService.props(
//
//    ),
//    "barcodes")

  webAPIActor ! Start()

  def receive: Receive = {
    case Shutdown() =>
      log.info(s"Shutting down system")
      webAPIActor ! Kill
  }

}



