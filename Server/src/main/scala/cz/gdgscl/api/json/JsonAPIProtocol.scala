package cz.gdgscl.api.json

import cz.gdgscl.api.WebAPIActor.APIError
import cz.gdgscl.api.barcode.BarcodeService.BarcodeInfo
import cz.gdgscl.api.company.CompanyService.{UnknownCompanies, CompanyInfo}
import cz.gdgscl.api.detector.DetectorService.DetectionResult
import spray.json._

object JsonAPIProtocol extends DefaultJsonProtocol {

  implicit val BarcodeInfoFormat: RootJsonFormat[BarcodeInfo] = jsonFormat1(BarcodeInfo)
  implicit val CompanyInfoFormat: RootJsonFormat[CompanyInfo] = jsonFormat1(CompanyInfo)
  implicit val APIErrorFormat: RootJsonFormat[APIError] = jsonFormat1(APIError)
  implicit val DetectionResultFormat: RootJsonFormat[DetectionResult] = jsonFormat2(DetectionResult)
  implicit val UnknownCompaniesFormat: RootJsonFormat[UnknownCompanies] = jsonFormat1(UnknownCompanies)

  implicit object DateJsonFormat extends RootJsonFormat[DetectionResult] {

    override def write(obj: DetectionResult) = JsObject(
    "company" -> JsString(obj.company.getOrElse("")),
    "error" -> JsBoolean(obj.error)
    )


    override def read(json: JsValue): DetectionResult = json match {
      case s: JsObject =>
        s.fields.get("error") match {
          case Some(JsBoolean(true)) => DetectionResult(error = true)
          case None | Some(JsBoolean(false)) =>
            s.fields.get("company") match {
              case Some(JsString(name)) => DetectionResult(Some(name))
              case _ => DetectionResult(error = true)
            }
        }
      case x => throw new DeserializationException(s"Expected JsObject to parse JSON, got ${x.getClass}")
    }
  }

}
