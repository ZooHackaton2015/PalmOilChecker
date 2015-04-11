package cz.gdgscl.api.routes

import akka.actor.ActorRef
import cz.gdgscl.api.barcode.BarcodeService.BarcodeInfo
import cz.gdgscl.api.utils.DefaultTimeout
import spray.http.StatusCodes
import spray.routing.HttpService

import scala.concurrent.ExecutionContext


trait BarcodeRoute extends HttpService with DefaultTimeout {


  def barcodeAPI(barcodeService: Option[ActorRef])(implicit execContenxt: ExecutionContext) = {
    pathPrefix("barcodes") {
      path(Segment)
      get {
        complete(
          StatusCodes.OK
        )
      }~
      post {
        entity(as[BarcodeInfo]) { info =>
          complete (info)
        }
      }
    }
  }

}
