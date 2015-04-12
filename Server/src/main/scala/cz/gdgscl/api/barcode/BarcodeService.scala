package cz.gdgscl.api.barcode

import akka.actor.{Actor, ActorLogging, Props}
import cz.gdgscl.api.barcode.BarcodeService._
import cz.gdgscl.api.utils.DefaultTimeout
import redis.clients.jedis.Jedis

object BarcodeService {

  def props(redis: Jedis,
            redisBarcodeMap: String,
            redisBarcodeUnkSet : String) = Props(new BarcodeService(redis, redisBarcodeMap,redisBarcodeUnkSet))

  case class BarcodeInfo(palmOil: Boolean)

  case class GetBarcodeInfo(code: String)

  case class NoBarcodeInfo()

  case class SetBarcodeInfo(code: String, info: BarcodeInfo)

  case class DeleteBarcodeInfo(code : String)

  case class BarcodeServiceERR(err: String)

  case class BarcodeServiceOK()

}

class BarcodeService( redis: Jedis,
                      redisBarcodeMap: String,
                      redisBarcodeUnkSet : String) extends Actor with ActorLogging with DefaultTimeout {

  override def receive: Receive = {

    case GetBarcodeInfo(code) =>
      val replyTo = sender()
      Option(redis.hget(redisBarcodeMap, code)) match {
        case Some(hasOil) => replyTo ! BarcodeInfo(hasOil.toBoolean)
        case None =>
          redis.sadd(redisBarcodeUnkSet,code)
          replyTo ! NoBarcodeInfo()
      }

    case SetBarcodeInfo(code, BarcodeInfo(palmOil)) =>
      redis.hset(redisBarcodeMap, code, palmOil.toString)
      sender() ! BarcodeServiceOK()

    case DeleteBarcodeInfo(code)  =>
      redis.hdel(redisBarcodeMap,code)
      sender() ! BarcodeServiceOK()
  }
}
