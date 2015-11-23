//
//  SwitchButton.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 24/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit

class SwitchButton: UIButton {

    var pressed: Bool = false {
        didSet {
            self.setNeedsDisplay()
        }
    }
    
    var buttonColor: UIColor {
        get {
            return self.pressed ? UIColor.whiteColor() : UIColor.whiteColor()
        }
    }

}
