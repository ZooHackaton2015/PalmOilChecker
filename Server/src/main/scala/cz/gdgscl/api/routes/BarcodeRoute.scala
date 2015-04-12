package cz.gdgscl.api.routes

import akka.actor.ActorRef
import akka.pattern._
import cz.gdgscl.api.WebAPIActor.APIError
import cz.gdgscl.api.barcode.BarcodeService._
import cz.gdgscl.api.utils.DefaultTimeout
import spray.http.StatusCodes
import spray.routing.HttpService

import scala.concurrent.ExecutionContext


trait BarcodeRoute extends HttpService with DefaultTimeout {

  import cz.gdgscl.api.json.JsonAPIProtocol._
  import spray.httpx.SprayJsonSupport._
  import spray.httpx.marshalling.ToResponseMarshallable._

  def barcodeAPI(barcodeService: ActorRef)(implicit execContenxt: ExecutionContext) = {
    pathPrefix("barcodes") {
      path(Segment) { code =>
        get {
          complete(
            (barcodeService ? GetBarcodeInfo(code)) map {
              case NoBarcodeInfo() => StatusCodes.NotFound -> None
              case x : BarcodeInfo => StatusCodes.OK -> Some(x)
            }
          )
        } ~
          post {
            entity(as[BarcodeInfo]) { info =>
              complete(
                (barcodeService ? SetBarcodeInfo(code,info)) map {
                  case BarcodeServiceOK() => StatusCodes.OK
                }
              )
            }
          } ~
          delete {
            complete(
              (barcodeService ? DeleteBarcodeInfo(code)) map {
                case BarcodeServiceOK() => StatusCodes.OK -> None
                case BarcodeServiceERR(err) => StatusCodes.InternalServerError -> Some(APIError(err))
              }
            )
          }
      }
    }
  }

}
