//
//  InfoStoryboardSegue.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 18/01/16.
//  Copyright © 2016 GDGSCL. All rights reserved.
//

import UIKit

class InfoStoryboardSegue: UIStoryboardSegue, UIViewControllerTransitioningDelegate {
    

    override func perform() {
        destinationViewController.modalPresentationStyle = .Custom
        destinationViewController.transitioningDelegate = self
        
        super.perform()
    }
    
    
    func presentationControllerForPresentedViewController(presented: UIViewController, presentingViewController presenting: UIViewController, sourceViewController source: UIViewController) -> UIPresentationController? {
        return InfoPresentationController(presentedViewController: presented,
            presentingViewController: presenting)
    }
}
