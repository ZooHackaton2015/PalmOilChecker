//
//  InfoViewController.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 18/01/16.
//  Copyright © 2016 GDGSCL. All rights reserved.
//

import UIKit

class InfoViewController: UIViewController, UIWebViewDelegate, UIViewControllerTransitioningDelegate {

    @IBOutlet weak var webView: UIWebView!
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        self.transitioningDelegate = self

        webView.scrollView.showsHorizontalScrollIndicator = false
        webView.scrollView.showsVerticalScrollIndicator = false
        webView.alpha = 0
        
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
    
    func webViewDidFinishLoad(webView: UIWebView) {
        UIView.beginAnimations(nil, context: nil)
        UIView.setAnimationDuration(0.3)
        webView.alpha = 1
        UIView.commitAnimations()
    }
    
    
// MARK: - Transition delegate
    
    
    func presentationControllerForPresentedViewController(presented: UIViewController, presentingViewController presenting: UIViewController, sourceViewController source: UIViewController) -> UIPresentationController? {
        return InfoPresentationController(presentedViewController: presented,
            presentingViewController: presenting)
    }

}
