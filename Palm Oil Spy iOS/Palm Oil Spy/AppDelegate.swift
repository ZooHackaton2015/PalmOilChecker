//
//  AppDelegate.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 11/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit
import Fabric
import Crashlytics


@UIApplicationMain
class AppDelegate: UIResponder, UIApplicationDelegate {
    
    /// Application Window.
    var window: UIWindow?
    
    /// Stored user settings.
    var settings: Settings?


    func application(
        _ application: UIApplication,
        didFinishLaunchingWithOptions launchOptions: [UIApplicationLaunchOptionsKey: Any]?
    ) -> Bool {
        // Fabric setup.
        Fabric.with([Crashlytics.self])
        
        // User settings.
        settings = Settings(storage: UserDefaults.standard)
        
        // Optimization hacks.
        preloadWebView()

        return true
    }

    
    //
    // MARK: - Helpers
    //
    
    
    /// UIWebView has slow initialization. We have preinit to speed up UI in application.
    func preloadWebView() {
        let _ = UIWebView()
    }
    
}

