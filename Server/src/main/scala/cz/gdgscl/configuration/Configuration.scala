package cz.gdgscl.configuration

import akka.actor.ActorSystem
import com.typesafe.config.{Config, ConfigFactory}
import redis.clients.jedis.{JedisPoolConfig, JedisPool}
import scaldi.Module

import scala.io.Source

class Configuration(implicit val system : ActorSystem) {


  val settingsModule = new Module {
    bind[Config] to ConfigFactory.load()

    bind[String] identifiedBy "version" to Source.fromInputStream(getClass.getClassLoader.getResourceAsStream("version.txt")).getLines().next()
  }

  val redis = new Module {

    bind[JedisPool] to new JedisPool(new JedisPoolConfig(), inject[Config].getString("app.redis.host"),
      inject[Config].getInt("app.redis.port"))

    bind[String] identifiedBy "redis.preproc.queue" to inject[Config].getString("app.redis.preproc.queue")
    bind[String] identifiedBy "redis.preproc.queue" to inject[Config].getString("app.redis.preproc.queue")

  }

  val api = new Module {
    bind[Int] identifiedBy "http.port" to inject[Config].getInt("app.api.http.port")
    bind[String] identifiedBy "http.host" to inject[Config].getString("app.api.http.host")
  } 

  val injector = settingsModule :: api
}