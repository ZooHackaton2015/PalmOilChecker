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
        
        if let url = Bundle.main.url(forResource: "home", withExtension: "html") {
            let request = URLRequest(url: url)
            webView.loadRequest(request)
        }
    }


    @IBAction func closeButtonPressed(_ sender: UIButton) {
        presentingViewController?.dismiss(animated: true, completion: nil)
    }
    
    
    override var preferredStatusBarStyle : UIStatusBarStyle {
        return UIStatusBarStyle.lightContent
    }
    
    
    //
    // MARK: - UIWebViewDelegate
    //
    
    
    func webView(
        _ webView: UIWebView,
        shouldStartLoadWith request: URLRequest,
        navigationType: UIWebViewNavigationType
    ) -> Bool {
        if let url = request.url, navigationType == .linkClicked {
            if #available(iOS 10.0, *) {
                UIApplication.shared.open(url, options: [:], completionHandler: nil)
            } else {
                UIApplication.shared.openURL(url)
            }
            
            return false
        }
        
        return true
    }
    
    
    func webViewDidFinishLoad(_ webView: UIWebView) {
        UIView.beginAnimations(nil, context: nil)
        UIView.setAnimationDuration(0.3)
        webView.alpha = 1
        UIView.commitAnimations()
    }
    
    
    //
    // MARK: - UIViewControllerTransitioningDelegate
    //
    
    
    func presentationController(
        forPresented presented: UIViewController,
        presenting: UIViewController?,
        source: UIViewController
    ) -> UIPresentationController? {
        return InfoPresentationController(presentedViewController: presented, presenting: presenting)
    }

}
