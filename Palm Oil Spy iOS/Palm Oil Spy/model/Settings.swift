//
//  Settings.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 18/01/16.
//  Copyright © 2016 GDGSCL. All rights reserved.
//

import Foundation


/// Handle user settings.
class Settings {
    
    /// Persistance storage
    let storage: PersistableStorage
    
    /// System sounds
    var soundsEnabled: Bool {
        get {
            return !storage.bool(forKey: "sounds")
        }
        set {
            storage.set(!newValue, forKey: "sounds")
            _ = storage.synchronize()
        }
    }
    
    /// First run indicator
    var isFirstRun: Bool {
        get {
            return !storage.bool(forKey: "firstrun")
        }
        set {
            storage.set(!newValue, forKey: "firstrun")
            _ = storage.synchronize()
        }
    }
    
    
    /// Initiate settings
    ///
    /// - Parameter storage: Proper persistent storage.
    init(storage: PersistableStorage) {
        self.storage = storage
    }
    
}


// Following code allows proper unit testing.

protocol PersistableStorage {
    func bool(forKey: String) -> Bool
    func set(_: Bool, forKey: String)
    func synchronize() -> Bool
}

extension UserDefaults: PersistableStorage {}

