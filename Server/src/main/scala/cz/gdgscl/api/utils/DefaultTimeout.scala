package cz.gdgscl.api.utils

import akka.util.Timeout
import scala.concurrent.duration._

trait DefaultTimeout {

  implicit val timeout = Timeout(10 seconds)
}
