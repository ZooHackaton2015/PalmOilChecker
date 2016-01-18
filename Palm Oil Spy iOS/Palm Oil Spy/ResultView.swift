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
    
    let sound = Sounds()
    var timer: NSTimer?
    var settings: Settings?
    
    var oilStatus: OilResults = .None {
        didSet {
            setNeedsDisplay()
            animateStatusChange()
        }
    }
    
    
    override func drawRect(rect: CGRect) {
        
        switch oilStatus {
        case .Good:
            if settings!.soundsEnabled {
                sound.playSound(.Good)
            }
            PalmOilGlyphs.drawThumbOK(frame: self.bounds, thumbColor: UIColor.greenColor())
            runCleaningTimer()
        case .Bad:
            if settings!.soundsEnabled {
                sound.playSound(.Bad)
            }
            PalmOilGlyphs.drawThumbKO(frame: self.bounds, thumbColor: UIColor.redColor())
            runCleaningTimer()
        case .None:
            print("Ready to go")
        case .Dunno:
            print("Something wrong happened with oil result status")
        }
    }
    
    func runCleaningTimer() {
        timer?.invalidate()
        timer = NSTimer.scheduledTimerWithTimeInterval(3.0,
            target: self,
            selector: "cleanStatus",
            userInfo: nil,
            repeats: false)
    }

    
    func cleanStatus() {
        oilStatus = .None
    }
    
    
    func animateStatusChange() {
        UIView.animateWithDuration(0.2, animations: {
            self.alpha = 0.0
        }) { (success) -> Void in
            UIView.animateWithDuration(0.4, animations: {
                self.alpha = 0.8
            })
        }
    }

}
