//
//  OilResult.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 18/06/2017.
//  Copyright © 2017 GDGSCL. All rights reserved.
//

import Foundation


/// All possible results for palm oil request.
///
/// - none: Nothing to be presented.
/// - good: Doesn't contain palm oil.
/// - bad: Does contain palm oil.
/// - unknow: We don't know yet.
enum OilCheckResult {
    case none
    case good
    case bad
    case unknow
}
