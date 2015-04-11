package cz.gdgscl.api

import akka.actor.{ActorLogging, ActorRef, Props}
import cz.gdgscl.api.WebAPIActor.Start
import cz.gdgscl.api.routes.BarcodeRoute
import cz.gdgscl.api.utils.{WebAPITimeout, DefaultTimeout}
import spray.routing.Directive.pimpApply
import spray.routing.HttpServiceActor

import scala.language.implicitConversions


object WebAPIActor {

  def props(appName: String) = Props(new WebAPIActor(appName) with WebAPITimeout)

  case class Start()

}

class WebAPIActor(appNameAndVersion: String) extends HttpServiceActor
with ActorLogging with BarcodeRoute
 {
  this: DefaultTimeout =>

  import context.dispatcher
  import spray.httpx.marshalling.ToResponseMarshallable._

  var barcodeServiceActor: Option[ActorRef] = None

  def receive = waitForInit


  def waitForInit: Receive = {
    case Start() =>
      context.actorSelection("../barcodes").resolveOne().foreach(x => barcodeServiceActor = Some(x))

      context.become(runRoute(
          barcodeAPI(barcodeServiceActor) ~
          appName
      ))
  }
  
  def appName = {
    pathEndOrSingleSlash {
      complete {
        appNameAndVersion
      }
    }
  }






}






