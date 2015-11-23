//
//  ResultView.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 12/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit

enum OilResults {
    case None
    case Good
    case Bad
    case Dunno
}

class ResultView: UIView {

    var oilStatus: OilResults = .None {
        didSet {
            self.setNeedsDisplay()
            animateStatusChange()
        }
    }
    
    
    override func drawRect(rect: CGRect) {
        switch oilStatus {
        case .Good:
            PalmOilGlyphs.drawThumbOK(frame: self.bounds, thumbColor: UIColor.greenColor())
        case .Bad:
            PalmOilGlyphs.drawThumbKO(frame: self.bounds, thumbColor: UIColor.redColor())
        default:
            print("Something wrong happened with oil result status")
        }
    }

    
    func animateStatusChange() {
        UIView.animateWithDuration(0.2, animations: { () -> Void in
            self.alpha = 0.0
        }) { (success) -> Void in
            UIView.animateWithDuration(0.4, animations: { () -> Void in
                self.alpha = 0.8
                }) { (success) -> Void in
                    
            }
        }
    }

}
