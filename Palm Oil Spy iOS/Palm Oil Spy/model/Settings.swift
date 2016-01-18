//
//  Settings.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 18/01/16.
//  Copyright © 2016 GDGSCL. All rights reserved.
//

import Foundation

class Settings {
    
    /// Persistance storage
    let userDefaults = NSUserDefaults.standardUserDefaults()
    
    /// System sounds
    var soundsEnabled: Bool = true {
        didSet {
            saveSettings()
        }
    }
    
    
    init() {
        loadSettings()
    }
    
    
    /**
        Persist settings
    */
    private func saveSettings() {
        userDefaults.setBool(soundsEnabled, forKey: "sounds")
        userDefaults.synchronize()
    }
    
    
    /**
        Restore settings from persist storage
    */
    private func loadSettings() {
        soundsEnabled = userDefaults.boolForKey("sounds") ?? true
    }
}
