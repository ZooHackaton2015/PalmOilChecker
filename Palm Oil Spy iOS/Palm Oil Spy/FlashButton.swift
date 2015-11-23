//
//  FlashButton.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 12/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit

class FlashButton: SwitchButton {

    override func drawRect(rect: CGRect) {
        super.drawRect(rect)
        print("Button Color: \(self.buttonColor)")
        PalmOilGlyphs.drawButtonFlash(frame: self.bounds, buttonColor: buttonColor)
        
        self.layer.shadowRadius = 10.0
        self.layer.shadowColor = self.pressed ? UIColor.redColor().CGColor : nil
    }

}
