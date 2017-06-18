//
//  InterfaceFeedback.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 17/01/16.
//  Copyright © 2016 GDGSCL. All rights reserved.
//

import Foundation
import AVFoundation


class InterfaceFeedback {
    
    
    /// List of possible sounds.
    ///
    /// - Good: Sound when product doesn't contain palm oil.
    /// - Bad: Sound when product does contain palm oil.
    enum Sound: String {
        case good = "good"
        case bad = "bad"
    }
    
    
    /// Player.
    let player: Player
    
    
    /// Initialize player.
    init(player: Player) {
        self.player = player
    }
    
    
    /// Play system sound.
    ///
    /// - Parameter sound: Sound that will be played if file exists.
    func play(sound: Sound) {
        guard let url = Bundle.main.url(forResource: sound.rawValue, withExtension: "wav")
            else { return }

        let item = AVPlayerItem(url: url)
        player.replaceCurrentItem(with: item)
        player.play()
    }
    
}


// Following code allows proper unit testing.

protocol Player {
    func play()
    func replaceCurrentItem(with item: AVPlayerItem?)
}

extension AVPlayer: Player {}
