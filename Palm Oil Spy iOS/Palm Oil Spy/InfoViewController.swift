//
//  InfoViewController.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 18/01/16.
//  Copyright © 2016 GDGSCL. All rights reserved.
//

import UIKit

class InfoViewController: UIViewController, UIWebViewDelegate {

    @IBOutlet weak var webView: UIWebView!
    
    
    override func viewDidLoad() {
        super.viewDidLoad()

        webView.scrollView.showsHorizontalScrollIndicator = false
        webView.scrollView.showsVerticalScrollIndicator = false
        
        if let url = NSBundle.mainBundle().URLForResource("home", withExtension: "html") {
            let request = NSURLRequest(URL: url)
            webView.loadRequest(request)
        }
    }

    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    

    @IBAction func closeButtonPressed(sender: UIButton) {
        presentingViewController?.dismissViewControllerAnimated(true, completion: nil)
    }
    
    
    override func preferredStatusBarStyle() -> UIStatusBarStyle {
        return UIStatusBarStyle.LightContent
    }
    
    
// MARK: - WebView delegate
    
    
    func webView(webView: UIWebView, shouldStartLoadWithRequest request: NSURLRequest, navigationType: UIWebViewNavigationType) -> Bool {
        if let url = request.URL where navigationType == .LinkClicked {
            UIApplication.sharedApplication().openURL(url)
            return false
        }
        return true
    }

}
