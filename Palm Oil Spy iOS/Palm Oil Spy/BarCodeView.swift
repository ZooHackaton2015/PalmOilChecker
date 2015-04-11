//
//  BarCodeView.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 11/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit

class BarCodeView: UIView {


    // Only override drawRect: if you perform custom drawing.
    // An empty implementation adversely affects performance during animation.
    override func drawRect(rect: CGRect) {
        // Drawing code
        self.layer.cornerRadius = 5.0
        PalmOilGlyphs.drawBarcodeBar(frame: self.bounds, borderColor: UIColor.whiteColor())
        
    }


}
