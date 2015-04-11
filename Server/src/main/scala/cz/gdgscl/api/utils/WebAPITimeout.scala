package cz.gdgscl.api.utils

import akka.util.Timeout

import scala.concurrent.duration._

trait WebAPITimeout extends DefaultTimeout{

  override implicit val timeout = Timeout(60 seconds)
}
