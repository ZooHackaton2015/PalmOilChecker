//
//  ResultView.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 12/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit
import AVFoundation


private extension Selector {
    static let cleanStatus = #selector(ResultView.cleanStatus)
}


class ResultView: UIView {
    
    let feedback: InterfaceFeedback
    var timer: Timer?
    var settings: Settings?
    var oilStatus: OilCheckResult = .none {
        didSet {
            setNeedsDisplay()
            animateStatusChange()
        }
    }
    
    required init?(coder aDecoder: NSCoder) {
        
        if #available(iOS 10.0, *) {
            self.feedback = InterfaceFeedback(generator: UINotificationFeedbackGenerator())
        } else {
            self.feedback = InterfaceFeedback(generator: nil)
        }
        
        super.init(coder: aDecoder)
        
        
    }
    
    
    override func draw(_ rect: CGRect) {
        switch oilStatus {
        case .good:
            if settings!.soundsEnabled {
                feedback.trigger(event: .good)
            }
            PalmOilGlyphs.drawThumbOK(frame: self.bounds, goodColor: PalmOilGlyphs.noOilColor)
            runCleaningTimer()
        case .bad:
            if settings!.soundsEnabled {
                feedback.trigger(event: .bad)
            }
            PalmOilGlyphs.drawThumbKO(frame: self.bounds, alertColor: PalmOilGlyphs.oilColor)
            runCleaningTimer()
        case .none:
            print("Ready to go")
        case .unknow:
            if settings!.soundsEnabled {
                feedback.trigger(event: .unknown)
            }
            PalmOilGlyphs.drawUnknown(frame: self.bounds, borderColor: UIColor.white)
            runCleaningTimer()
        }
    }
    
    
    func runCleaningTimer() {
        timer?.invalidate()
        timer = Timer.scheduledTimer(
            timeInterval: 3.0,
            target: self,
            selector: .cleanStatus,
            userInfo: nil,
            repeats: false
        )
    }

    
    func cleanStatus() {
        oilStatus = .none
    }
    
    
    func animateStatusChange() {
        UIView.animate(withDuration: 0.2, animations: { [weak self] in
            self?.alpha = 0.0
        }, completion: { (success) -> Void in
            UIView.animate(withDuration: 0.4) { [weak self] in
                self?.alpha = 0.8
            }
        }) 
    }

}
