//
//  ShadowView.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 19/01/16.
//  Copyright © 2016 GDGSCL. All rights reserved.
//

import UIKit


class ShadowView: UIView {

    
    override func draw(_ rect: CGRect) {
        PalmOilGlyphs.drawShadow(frame: rect)
    }

}
