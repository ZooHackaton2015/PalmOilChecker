package cz.gdgscl

import akka.actor.ActorSystem
import cz.gdgscl.api.APIBootstrap
import cz.gdgscl.configuration.Configuration
import scaldi.Injectable

object Main extends App with Injectable {

  implicit val system = ActorSystem("Palmoil_Checker")
  implicit val diContext = new Configuration().injector

  val boss = system.actorOf(APIBootstrap.props(diContext), name = "boot")

}

