package cz.gdgscl.configuration

import akka.actor.ActorSystem
import com.typesafe.config.{Config, ConfigFactory}
import redis.clients.jedis.{JedisPoolConfig, JedisPool}
import scaldi.Module

import scala.io.Source

class Configuration(implicit val system : ActorSystem) {


  val settingsModule = new Module {
    bind[Config] to ConfigFactory.load()

    bind[String] identifiedBy "app.name" to "Palm Oil Checker " + Source.fromInputStream(getClass.getClassLoader.getResourceAsStream("version.txt")).getLines().next()
  }

  val redis = new Module {

    bind[JedisPool] to new JedisPool(new JedisPoolConfig(), inject[Config].getString("app.redis.host"),
      inject[Config].getInt("app.redis.port"))

    bind[String] identifiedBy "redisCompanyMap" to inject[Config].getString("app.redis.companyMap")
    bind[String] identifiedBy "redisCompanyUnkSet" to inject[Config].getString("app.redis.companyUnkSet")
    bind[String] identifiedBy "redisBarcodeMap" to inject[Config].getString("app.redis.barcodeMap")
    bind[String] identifiedBy "redisBarcodeUnkSet" to inject[Config].getString("app.redis.barcodeUnkSet")
  }

  val scripts = new Module {
    bind[String] identifiedBy "scriptsDir" to inject[Config].getString("app.scripts")
  }

  val api = new Module {
    bind[Int] identifiedBy "http.port" to inject[Config].getInt("app.api.http.port")
    bind[String] identifiedBy "http.host" to inject[Config].getString("app.api.http.host")
  } 

  val injector = settingsModule :: redis :: api :: scripts
}