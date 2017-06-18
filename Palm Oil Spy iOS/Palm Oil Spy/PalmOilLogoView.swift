//
//  PalmOilLogoView.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 24/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit


class PalmOilLogoView: UIView {

    
    override func draw(_ rect: CGRect) {
        PalmOilGlyphs.drawPalmOilLogo(frame: self.bounds, borderColor: UIColor.white)
    }

}
