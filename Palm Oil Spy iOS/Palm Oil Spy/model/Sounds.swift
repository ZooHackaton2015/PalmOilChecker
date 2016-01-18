//
//  Sounds.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 17/01/16.
//  Copyright © 2016 GDGSCL. All rights reserved.
//

import Foundation
import AVFoundation

enum PalmOilSounds: String {
    case Good = "good"
    case Bad = "bad"
}

class Sounds {
    
    let player: AVPlayer
    
    init() {
        player = AVPlayer()
    }
    
    
    /**
        Play system sound
     
        - parameter sound: Sound that will be played if file exists
    */
    func playSound(sound: PalmOilSounds) {
        guard let url = NSBundle.mainBundle().URLForResource(sound.rawValue, withExtension: "wav")
            else { return }

        let item = AVPlayerItem(URL: url)
        player.replaceCurrentItemWithPlayerItem(item)
        player.play()
    }
}
