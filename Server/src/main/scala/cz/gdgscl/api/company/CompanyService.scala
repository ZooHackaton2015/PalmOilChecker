package cz.gdgscl.api.company

import akka.actor.{Actor, ActorLogging, Props}
import cz.gdgscl.api.company.CompanyService._
import cz.gdgscl.api.utils.DefaultTimeout
import redis.clients.jedis.Jedis
import scala.collection.JavaConversions._
object CompanyService {

  def props(redis: Jedis,
            redisCompanyMap: String,
            redisUnkCompanySet: String) = Props(new CompanyService(redis, redisCompanyMap, redisUnkCompanySet))


  case class NoCompany()

  case class CompanyInfo(palmOil: Boolean)

  case class GetCompanyInfo(name: String)

  case class CompanyServiceERR(err: String)

  case class SetCompanyInfo(name: String, palmOil: Boolean)

  case class DeleteCompanyInfo(name : String)

  case class CompanyServiceOK()

  case class GetUnknownCompanies()

  case class UnknownCompanies(names : List[String])

}


class CompanyService(
                      redis: Jedis,
                      redisCompanyMap: String,
                      redisUnkCompanySet: String) extends Actor with ActorLogging with DefaultTimeout {

  override def receive: Receive = {

    case GetCompanyInfo(comp) =>
      val replyTo = sender()
      Option(redis.hget(redisCompanyMap, comp)) match {
        case Some(oilInfo) => replyTo ! CompanyInfo(oilInfo.toBoolean)
        case None => NoCompany()
      }

    case SetCompanyInfo(comp, palmOil: Boolean) =>
      val pipe = redis.pipelined()
      pipe.hset(redisCompanyMap, comp, palmOil.toString)
      pipe.srem(redisUnkCompanySet, comp)
      pipe.sync()
      sender() ! CompanyServiceOK()

    case GetUnknownCompanies() =>
      sender() ! UnknownCompanies(redis.smembers(redisUnkCompanySet).toList)

    case DeleteCompanyInfo(comp) =>
      val pipe = redis.pipelined()
      pipe.hdel(redisCompanyMap,comp)
      pipe.srem(redisUnkCompanySet,comp)
      pipe.sync()
      sender() ! CompanyServiceOK()

  }
}
