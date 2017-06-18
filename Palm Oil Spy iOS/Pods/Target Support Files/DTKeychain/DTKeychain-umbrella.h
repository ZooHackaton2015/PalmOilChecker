#ifdef __OBJC__
#import <UIKit/UIKit.h>
#else
#ifndef FOUNDATION_EXPORT
#if defined(__cplusplus)
#define FOUNDATION_EXPORT extern "C"
#else
#define FOUNDATION_EXPORT extern
#endif
#endif
#endif

#import "DTKeychain.h"
#import "DTKeychainGenericPassword.h"
#import "DTKeychainItem.h"

FOUNDATION_EXPORT double DTKeychainVersionNumber;
FOUNDATION_EXPORT const unsigned char DTKeychainVersionString[];

