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
    var soundsEnabled: Bool = true
    
    /// First run indicator
    var firstRun: Bool = true
    
    
    init() {
        loadSettings()
    }
    
    
    /**
        Persist settings
    */
    func saveSettings() {
        userDefaults.setBool(soundsEnabled, forKey: "sounds")
        userDefaults.setBool(firstRun, forKey: "firstrun")
        userDefaults.synchronize()
        print("Settings saved: firstRun: \(firstRun), sounds: \(soundsEnabled)")
    }
    
    
    /**
        Restore settings from persist storage
    */
    private func loadSettings() {
        firstRun = userDefaults.objectForKey("firstrun") != nil ? userDefaults.boolForKey("firstrun") : true
        soundsEnabled = userDefaults.objectForKey("firstrun") != nil ? userDefaults.boolForKey("sounds") : true
        print("Settings loaded: firstRun: \(firstRun), sounds: \(soundsEnabled)")
    }
}
