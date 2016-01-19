//
//  ShadowView.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 19/01/16.
//  Copyright © 2016 GDGSCL. All rights reserved.
//

import UIKit

class ShadowView: UIView {


    // Only override drawRect: if you perform custom drawing.
    // An empty implementation adversely affects performance during animation.
    override func drawRect(rect: CGRect) {
        // Drawing code
        PalmOilGlyphs.drawShadow(frame: rect)
    }


}
