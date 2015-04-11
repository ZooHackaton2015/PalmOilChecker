import sbtfilter.Plugin.FilterKeys._

name := "server"

version := "0.1"

scalaVersion := "2.11.6"

val akkaV = "2.3.9"
val scaldiV = "0.3.2"
val sprayV = "1.3.2"
val sprayJsonV = "1.3.1"
val logbackV = "1.1.2"
val slf4jV = "1.7.6"
val scalaTestV = "2.2.1"
val jedisV = "2.7.0"

libraryDependencies ++= Seq(
  "com.typesafe.akka" %% "akka-actor" % akkaV,
  "com.typesafe.akka" %% "akka-agent" % akkaV,
  "com.typesafe.akka" %% "akka-testkit" % akkaV,
  "com.typesafe.akka" %% "akka-cluster" % akkaV,
  "com.typesafe.akka" %% "akka-kernel" % akkaV,
  "com.typesafe.akka" %% "akka-slf4j" % akkaV,
  "org.scalatest" %% "scalatest" % scalaTestV % "test",
  "org.scaldi" %% "scaldi" % scaldiV,
  "io.spray" %% "spray-can" % sprayV,
  "io.spray" %% "spray-routing" % sprayV,
  "io.spray" %% "spray-testkit" % sprayV % "test",
  "io.spray" %% "spray-json" % sprayJsonV,
  "io.spray" %% "spray-client" % sprayV,
  "ch.qos.logback" % "logback-classic" % logbackV,
  "org.slf4j" % "slf4j-api" % slf4jV,
  "redis.clients" % "jedis" % jedisV
)


filterSettings

includeFilter in (Compile, filterResources) ~= { f => f || "*.txt" }

mainClass in Compile := Some("cz.nanotrix.Kernel")

