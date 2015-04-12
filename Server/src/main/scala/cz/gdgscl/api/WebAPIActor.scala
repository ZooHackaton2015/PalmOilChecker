package cz.gdgscl.api

import akka.actor.{ActorLogging, Props}
import cz.gdgscl.api.WebAPIActor.Start
import cz.gdgscl.api.routes.{BarcodeRoute, CompanyRoute}
import cz.gdgscl.api.utils.{DefaultTimeout, WebAPITimeout}
import spray.routing.Directive.pimpApply
import spray.routing.HttpServiceActor

import scala.language.implicitConversions


object WebAPIActor {

  def props(appName: String) = Props(new WebAPIActor(appName) with WebAPITimeout)

  case class APIError(err : String)

  case class Start()

}

class WebAPIActor(appNameAndVersion: String) extends HttpServiceActor
with ActorLogging with BarcodeRoute with CompanyRoute
 {
  this: DefaultTimeout =>

  import context.dispatcher
  import spray.httpx.marshalling.ToResponseMarshallable._



  def receive = waitForInit


  def waitForInit: Receive = {
    case Start() =>
      for {
        barcode <- context.actorSelection("../barcode").resolveOne()
        company <- context.actorSelection("../company").resolveOne()
      } yield {
        context.become(runRoute(
          barcodeAPI(barcode) ~
            companyAPI(company) ~
            appName
        ))}
  }
  
  def appName = {
    pathEndOrSingleSlash {
      complete {
        appNameAndVersion
      }
    }
  }






}






