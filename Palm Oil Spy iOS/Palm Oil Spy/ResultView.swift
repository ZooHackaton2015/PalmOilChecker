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
    
    let sound = InterfaceFeedback(player: AVPlayer())
    var timer: Timer?
    var settings: Settings?
    var oilStatus: OilCheckResult = .none {
        didSet {
            setNeedsDisplay()
            animateStatusChange()
        }
    }
    
    
    override func draw(_ rect: CGRect) {
        switch oilStatus {
        case .good:
            if settings!.soundsEnabled {
                sound.play(sound: .good)
            }
            PalmOilGlyphs.drawThumbOK(frame: self.bounds, thumbColor: UIColor.green)
            runCleaningTimer()
        case .bad:
            if settings!.soundsEnabled {
                sound.play(sound: .bad)
            }
            PalmOilGlyphs.drawThumbKO(frame: self.bounds, thumbColor: UIColor.red)
            runCleaningTimer()
        case .none:
            print("Ready to go")
        case .unknow:
            print("Something wrong happened with oil result status")
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
