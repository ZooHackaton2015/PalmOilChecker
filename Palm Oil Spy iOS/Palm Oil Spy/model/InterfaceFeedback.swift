//
//  InterfaceFeedback.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 17/01/16.
//  Copyright © 2016 GDGSCL. All rights reserved.
//

import UIKit
import AVFoundation


class InterfaceFeedback {
    
    
    /// List of possible feedbacks.
    ///
    /// - good: Feedback when product doesn't contain palm oil.
    /// - bad: Feedback when product does contain palm oil.
    /// - unknown: Feedback when product does list in online DB.
    enum Events {
        case good
        case bad
        case unknown
    }
    
    
    /// Feedback generator.
    let generator: Generator?
    
    
    /// Initialize player.
    init(generator: Generator?) {
        self.generator = generator
    }
    
    
    /// Play system sound.
    ///
    /// - Parameter event: Events that will be presented.
    func trigger(event: Events) {
        if let generator = generator {
            switch event {
            case .good: generator.notificationOccurred(.success)
            case .bad: AudioServicesPlaySystemSound(kSystemSoundID_Vibrate)
                //generator.notificationOccurred(.error)
            case .unknown: generator.notificationOccurred(.warning)
            }
        } else {
            AudioServicesPlaySystemSound(kSystemSoundID_Vibrate)
        }
    }
    
}


// Following code allows proper unit testing.

protocol Generator {
   func notificationOccurred(_ notificationType: UINotificationFeedbackType)
}

@available(iOS 10.0, *)
extension UINotificationFeedbackGenerator: Generator {}
