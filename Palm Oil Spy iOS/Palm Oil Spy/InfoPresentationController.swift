//
//  InfoPresentationController.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 18/01/16.
//  Copyright © 2016 GDGSCL. All rights reserved.
//

import UIKit

class InfoPresentationController: UIPresentationController {

    let dimmingView = UIView()
    
    override func presentationTransitionWillBegin() {
        if let containerView = containerView {
            dimmingView.backgroundColor = UIColor(red: 0, green: 0, blue: 0, alpha: 0.5)
            dimmingView.frame = containerView.bounds
            dimmingView.alpha = 0.0
            containerView.insertSubview(dimmingView, atIndex: 0)
            
            presentedViewController.view.layer.cornerRadius = 10.0
            
            presentedViewController.transitionCoordinator()?.animateAlongsideTransition({
                context in
                self.dimmingView.alpha = 1.0
                }, completion: nil)
            
        }
    }
    
    override func dismissalTransitionWillBegin() {
        presentedViewController.transitionCoordinator()?.animateAlongsideTransition({
            context in
            self.dimmingView.alpha = 0.0
            }, completion: nil)
    }
    
    override func frameOfPresentedViewInContainerView() -> CGRect {
        return containerView?.bounds.insetBy(dx: 20, dy: 30) ?? CGRectZero
    }
    
}
