package cz.gdgscl.api.json

import cz.gdgscl.api.barcode.BarcodeService.BarcodeInfo
import cz.gdgscl.api.detector.DetectorService.DetectionResult
import spray.json._

object JsonAPIProtocol extends DefaultJsonProtocol {

  implicit val BarcodeInfoFormat: RootJsonFormat[BarcodeInfo] = jsonFormat2(BarcodeInfo)
  implicit val DetectionResultFormat: RootJsonFormat[DetectionResult] = jsonFormat1(DetectionResult)


}
