//
//  ApiConnector.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 12/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit

class ApiConnector: NSObject, NSURLSessionDataDelegate {
    
    var session: NSURLSession?
    
    override init() {
        super.init()
        
        self.session = NSURLSession(configuration: NSURLSessionConfiguration.defaultSessionConfiguration())
    }
    
    var delegate: ApiConnectorDelegate?
    
    // MARK: api
    
    func eanCodeIdentify(code: String) {
        var url = self.urlAddress(code)
        if let dataTask = session?.dataTaskWithURL(url!, completionHandler: { (data: NSData?, response: NSURLResponse?, error: NSError?) -> Void in
            
            if let response = response as? NSHTTPURLResponse {
                if response.statusCode == 404 {
                    self.delegate?.didRecieveAnswer(OilResults.Dunno)
                }
                else if response.statusCode == 200 {
                    
                    var responseData = NSJSONSerialization.JSONObjectWithData(data!, options: NSJSONReadingOptions.allZeros, error: nil) as! [String:Bool]
                    
                    if responseData["palmOil"] == true {
                        self.delegate?.didRecieveAnswer(OilResults.Bad)
                    }
                    else {
                        self.delegate?.didRecieveAnswer(OilResults.Good)
                    }
                }
            }
        
            // process response
        }) as NSURLSessionDataTask? {
            dataTask.resume()
        }
    }
   
    func urlAddress(code: String) -> NSURL? {
        let path = "http://10.38.131.166:80/v1/barcodes/\(code)"
        let url = NSURL(string: path)
        return url
    }
}

protocol ApiConnectorDelegate {
    func didRecieveAnswer(answer: OilResults)
}