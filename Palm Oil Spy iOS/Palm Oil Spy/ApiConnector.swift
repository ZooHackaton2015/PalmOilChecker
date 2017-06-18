//
//  ApiConnector.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 12/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit


class ApiConnector: NSObject, URLSessionDataDelegate {
    
    weak var delegate: ApiConnectorDelegate?
    
    var session: URLSession?
    
    
    override init() {
        super.init()
        
        self.session = URLSession(configuration: URLSessionConfiguration.default)
    }
    

    //
    // MARK: - API
    //
    
    
    func eanCodeIdentify(_ code: String) {
        let url = self.urlAddress(code)
        if let dataTask = session?.dataTask(with: url!, completionHandler: {
            (data: Data?, response: URLResponse?, error: NSError?) in

            guard let response = response as? HTTPURLResponse,
                let data = data else {return}
            
            switch response.statusCode {
            case 404:
                self.delegate?.didRecieveAnswer(.unknow)
            case 200:
                do {
                    guard let responseData = try JSONSerialization.jsonObject(
                            with: data,
                            options: JSONSerialization.ReadingOptions()
                        ) as? [String:Bool],
                        let containsOil = responseData["contains-oil"] as Bool?
                        else { return }
                    self.delegate?.didRecieveAnswer(containsOil ? .bad : .good)
                } catch {}
            default: break
            }
            
        } as! (Data?, URLResponse?, Error?) -> Void) as URLSessionDataTask? {
            dataTask.resume()
        }
    }
   
    
    func urlAddress(_ code: String) -> URL? {
        let path = "\(apiURLPath)/barcodes/\(code)"
        let url = URL(string: path)
        
        return url
    }
    
}

protocol ApiConnectorDelegate: class {
    func didRecieveAnswer(_ answer: OilCheckResult)
}
