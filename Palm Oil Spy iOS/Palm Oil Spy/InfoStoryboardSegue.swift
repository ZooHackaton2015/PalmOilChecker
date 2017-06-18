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
        destination.modalPresentationStyle = .custom
        destination.transitioningDelegate = self
        
        super.perform()
    }
    
    
    func presentationController(
        forPresented presented: UIViewController,
        presenting: UIViewController?, source: UIViewController
    ) -> UIPresentationController? {
        return InfoPresentationController(presentedViewController: presented, presenting: presenting)
    }
    
}
