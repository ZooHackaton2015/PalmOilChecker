package cz.gdgscl

import akka.actor.ActorSystem
import akka.kernel.Bootable
import cz.gdgscl.api.APIBootstrap
import cz.gdgscl.configuration.Configuration
import scaldi.Injectable

class Kernel extends Bootable with Injectable {

  implicit val system = ActorSystem("PalmOil_Checker")
  implicit val diContext = new Configuration().injector


  override def startup(): Unit = {
    system.actorOf(APIBootstrap.props(diContext), name = "bootstrap")
  }

  override def shutdown(): Unit = {
    system.shutdown()

  }

}
