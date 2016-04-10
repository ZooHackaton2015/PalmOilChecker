#!/bin/env node
//  OpenShift PalmOil Node application
var express = require('express');
var fs      = require('fs');
var MongoClient = require('mongodb').MongoClient;
var assert = require('assert');



/**
 *  PalmOil application.
 */
var PalmOilApp = function() {

    //  Scope.
    var self = this;

    /*  ================================================================  */
    /*  Helper functions.                                                 */
    /*  ================================================================  */

    /**
     *  Set up server IP address and port # using env variables/defaults.
     */
    self.setupVariables = function() {
        //  Set the environment variables we need.
        self.ipaddress = process.env.OPENSHIFT_NODEJS_IP;
        self.port      = process.env.OPENSHIFT_NODEJS_PORT || 8080;

        if (typeof self.ipaddress === "undefined") {
            //  Log errors on OpenShift but continue w/ 127.0.0.1 - this
            //  allows us to run/test the app locally.
            console.warn('No OPENSHIFT_NODEJS_IP var, using 127.0.0.1');
            self.ipaddress = "127.0.0.1";
        };
    };


    /**
     *  Populate the cache.
     */
    self.populateCache = function() {
        if (typeof self.zcache === "undefined") {
            self.zcache = { 'index.html': '' };
        }

        //  Local cache for static content.
        self.zcache['index.html'] = fs.readFileSync('./index.html');
    };


    /**
     *  Retrieve entry (content) from cache.
     *  @param {string} key  Key identifying content to retrieve from cache.
     */
    self.cache_get = function(key) { return self.zcache[key]; };


    /**
     *  terminator === the termination handler
     *  Terminate server on receipt of the specified signal.
     *  @param {string} sig  Signal to terminate on.
     */
    self.terminator = function(sig){
        if (typeof sig === "string") {
           console.log('%s: Received %s - terminating sample app ...',
                       Date(Date.now()), sig);
           process.exit(1);
        }
        console.log('%s: Node server stopped.', Date(Date.now()) );
    };


    /**
     *  Setup termination handlers (for exit and a list of signals).
     */
    self.setupTerminationHandlers = function(){
        //  Process on exit and signals.
        process.on('exit', function() { self.terminator(); });

        // Removed 'SIGPIPE' from the list - bugz 852598.
        ['SIGHUP', 'SIGINT', 'SIGQUIT', 'SIGILL', 'SIGTRAP', 'SIGABRT',
         'SIGBUS', 'SIGFPE', 'SIGUSR1', 'SIGSEGV', 'SIGUSR2', 'SIGTERM'
        ].forEach(function(element, index, array) {
            process.on(element, function() { self.terminator(element); });
        });
    };


    /*  ================================================================  */
    /*  App server functions (main app logic here).                       */
    /*  ================================================================  */

    self.watermark = -1;
    self.barcodes = null;
    self.mongourl = 'mongodb://admin:QzfJY8-mhELh@570a10472d52717c48000004-zoohackaton.rhcloud.com:54861/palmoiladmin'
    //self.mongourl = 'mongodb://admin:PHNYiG-lLd7W@ex-std-node683.prod.rhcloud.com:27017/admin';
    //self.mongourl = 'mongodb://admin:PHNYiG-lLd7W@127.11.163.2:27017/admin';
    self.mongolocalurl = 'mongodb://localhost:27017/test';

    /**
     *  Create the routing table entries + handlers for the application.
     */
    self.createRoutes = function() {
        self.routes = { };

        self.routes['/admin'] = function(req, res) {
            // TODO redirect to admin REST service
            var link = "http://i.imgur.com/kmbjB.png";
            res.send("<html><body><img src='" + link + "'></body></html>");
        };

        self.routes['/'] = function(req, res) {
            res.setHeader('Content-Type', 'text/html');
            // cached file
            res.send(self.cache_get('index.html') );
        };

        /*
         * PalmOil BEGIN
         */

        // Use HTTP GET to determine DB connection
        self.routes['/db-connection-status'] = function(req, res) {
            console.log("Connecting to %s", self.mongourl);
            MongoClient.connect(self.mongourl, function(err, db) {
              console.log("Connected correctly to server.");
              assert.equal(null, err);
              db.close();

              res.status(200);
              res.setHeader('Content-Type', 'application/json');
              res.send('{ "db-connection-status" : "OK" }');
            });
        };

        // Use HTTP GET to force refresh from DB and get watermark
        self.routes['/watermark'] = function(req, res) {
            MongoClient.connect(self.mongourl , function(err, db) {
              if(err) {
                return console.dir(err);
              }

              var collection = db.collection('products');
              var query = { "timestamp": { $gt: self.watermark } };
              console.log('Watermark query: %s', JSON.stringify(query)); // TODO debug to remove
              collection.find(query).toArray(function(err, items) {
                  var i = 0;
                  if(items) {
                    if(!self.barcodes) {
                      self.barcodes={};
                    }
                    // TODO IMPROVE: consider more efficient way than looping
                    for(i=0; i<items.length; i++) {
                      self.barcodes[items[i].barcode] = items[i].safe;
                      if(items[i].timestamp>self.watermark) {
                        self.watermark=items[i].timestamp;
                      }
                      console.log('  %s %s', self.watermark, JSON.stringify(self.barcodes)); // TODO debug to remove
                    }
                  } else {
                    self.barcodes = {};
                    console.log('No data loaded from database!');
                  }

                  db.close();
                  res.status(200);
                  res.send('{ "watermark" : '+self.watermark+', "loaded" : '+i+' }');
              })
          })
        };

        // TODO this is mock code to be removed - use HTTP GET to force refresh from DB
        self.routes['/filesystem'] = function(req, res) {
            fs.readFile( __dirname + "/" + "barcodes.json", 'utf8', function (err, data) {
               self.barcodes = JSON.parse( data ).barcodes;
               res.setHeader('Content-Type', 'application/json');
               res.status(200);
               res.send('{ "cached-barcodes" : '+ barcodes.barcodes.lenght +' }');
            })
        };

        // Determine barcode safety
        self.routes['/barcodes/:id'] = function(req, res) {
           if(self.barcodes == null) {
               // load database if not initialized
               res.status(302);
               res.setHeader('Location', 'http://palmoil-zoohackaton.rhcloud.com/watermark');
               res.end();
           } else {
             if(self.barcodes[req.params.id] === undefined) {
               res.status(404);
               res.end();
             } else {
               res.setHeader('Content-Type', 'application/json');
               res.status(200);
               if( self.barcodes[req.params.id] ) {
                 res.send('{ "contains-oil": true }');
               } else {
                 res.send('{ "contains-oil": false }');
               }
             }
           }
        };

        /*
         * PalmOil END
         */
    };


    /**
     *  Initialize the server (express) and create the routes and register
     *  the handlers.
     */
    self.initializeServer = function() {
        self.createRoutes();
        self.app = express.createServer();

        //  Add handlers for the app (from the routes).
        for (var r in self.routes) {
            self.app.get(r, self.routes[r]);
        }
    };


    /**
     *  Initializes the sample application.
     */
    self.initialize = function() {
        self.setupVariables();
        self.populateCache();
        self.setupTerminationHandlers();

        // Create the express server and routes.
        self.initializeServer();
    };


    /**
     *  Start the server (starts up the sample application).
     */
    self.start = function() {
        //  Start the app on the specific interface (and port).
        self.app.listen(self.port, self.ipaddress, function() {
            console.log('%s: Node server started on %s:%d ...',
                        Date(Date.now() ), self.ipaddress, self.port);
        });
    };

};   /*  Palm Oil Application.  */



/**
 *  main():  Main code.
 */
var zapp = new PalmOilApp();
zapp.initialize();
zapp.start();
