//
//  Flashlight.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 18/01/16.
//  Copyright © 2016 GDGSCL. All rights reserved.
//

import Foundation
import AVFoundation

class Flashlight {
    
    
    let device: AVCaptureDevice
    
    
    /**
        - parameter device: Current device
    */
    init(device: AVCaptureDevice) {
        self.device = device
    }
    
    
    /**
        Turn torch on
    */
    func turnOn() {
        guard device.hasTorch else { return }
        
        do {
            try device.lockForConfiguration()
            device.torchMode = .On
            device.unlockForConfiguration()
        }
        catch {
            
        }
    }
    
    
    /**
        Turn torch off
    */
    func turnOff() {
        guard device.hasTorch else { return }        
        
        do {
            try device.lockForConfiguration()
            device.torchMode = .Off
            device.unlockForConfiguration()
        }
        catch {
            
        }
    }
    
}