# PalOilChecker Server Module

## Requirements

 * JDK 1.7 +
 * SBT 0.13 +
 * Scala 2.11 (for development, can be downloaded using sbt)
 
## Installation
 
 1. sbt stage (In project root - Server)
 
## Configuration 

 Server is configured using environment variables (there is also an option for specifying own application.conf).
 
 For additional Akka configuration options see [Configuration] (http://doc.akka.io/docs/akka/2.3.9/general/configuration.html)

 
 * POC_HTTP_PORT (default 80) - on which port to listen
 * POC_HTTP_HOST (default 0.0.0.0) - on which interface to listen
 * POC_REDIS_HOST (default 127.0.0.1) - redis host
 * POC_REDIS_PORT (default 6379) - redis port
 * POC_DETECTOR_SCRIPTS_DIR (required) - directory with company detector scripts (only python files - ending with _.py_ - are supported)

## Deploy

 Application is deployed using [Akka Microkernel] (http://doc.akka.io/docs/akka/2.3.9/scala/microkernel.html)
 
 1. in target/universal/stage
 1. bin/server


## API documentation

 Current version of API is avaiablet at [POC Apiary] (http://docs.palmoilchecker.apiary.io)

## Frameworks

 * Akka
 * Spray
 * Scaldi
 * Jedis
 
 See build.sbt for current versions
 
## Contributors

 * Ondøej Smola