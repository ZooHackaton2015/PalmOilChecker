package cz.gdgscl.api.routes

import akka.actor.ActorRef
import akka.pattern._
import cz.gdgscl.api.company.CompanyService._
import cz.gdgscl.api.utils.DefaultTimeout
import spray.http.StatusCodes
import spray.routing.HttpService

import scala.concurrent.ExecutionContext


trait CompanyRoute extends HttpService with DefaultTimeout {

  import cz.gdgscl.api.json.JsonAPIProtocol._
  import spray.httpx.SprayJsonSupport._
  import spray.httpx.marshalling.ToResponseMarshallable._

  def companyAPI(companyService: ActorRef)(implicit execContenxt: ExecutionContext) = {
    pathPrefix("v1" / "companies") {
      path("unknown") {
        pathEnd {
          get {
            complete (
              (companyService ? GetUnknownCompanies()) map {
                case x : UnknownCompanies => StatusCodes.OK -> x
              }
            )
          }
        }
      } ~
      path(Segment) { id =>
        get {
          complete(
            (companyService ? GetCompanyInfo(id)) map {
              case NoCompany() => StatusCodes.NotFound -> None
              case x : CompanyInfo => StatusCodes.OK -> Some(x)
            }
          )
        } ~
          post {
            entity(as[CompanyInfo]) { info =>
              complete(
                (companyService ?  SetCompanyInfo(id,info)) map {
                  case CompanyServiceOK() => StatusCodes.OK
                }
              )
            }
          } ~
          delete {
            complete(
              (companyService ? DeleteCompanyInfo(id)) map {
                case CompanyServiceOK() => StatusCodes.OK
              }
            )
          }
      }
    }
  }

}
