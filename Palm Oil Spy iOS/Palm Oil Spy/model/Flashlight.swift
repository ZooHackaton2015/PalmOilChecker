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
    
    
    /// Current device.
    let device: AVCaptureDevice
    
    
    /// Create instance of Flashlight.
    ///
    /// - Parameter device: Current device.
    init(device: AVCaptureDevice) {
        self.device = device
    }
    
    
    /// Turn on torch.
    func turnOn() {
        guard device.hasTorch else { return }
        
        do {
            try device.lockForConfiguration()
            device.torchMode = .on
            device.unlockForConfiguration()
        }
        catch {
            // FIXME: log error
        }
    }
    
    
    /// Turn off torch.
    func turnOff() {
        guard device.hasTorch else { return }        
        
        do {
            try device.lockForConfiguration()
            device.torchMode = .off
            device.unlockForConfiguration()
        }
        catch {
            // FIXME: log error
        }
    }
    
}
