//
//  BarCodeView.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 11/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit

class BarCodeView: UIView {

    override func drawRect(rect: CGRect) {
        self.layer.cornerRadius = 5.0
        PalmOilGlyphs.drawBarcodeBar(frame: self.bounds, borderColor: UIColor.whiteColor())
    }

}
