//
//  FlashButton.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 12/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit

class FlashButton: UIButton {
    override func drawRect(rect: CGRect) {
        super.drawRect(rect)
        
        PalmOilGlyphs.drawButtonFlash(frame: self.bounds, buttonColor: UIColor.whiteColor())
    }
}
