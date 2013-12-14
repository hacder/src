//
//  AppDelegate.h
//  WeiXin
//
//  Created by TanHao on 13-8-25.
//  Copyright (c) 2013年 http://www.tanhao.me. All rights reserved.
//

#import <Cocoa/Cocoa.h>

@class WXAccess;
@class WXMainWindowController;
@interface AppDelegate : NSObject <NSApplicationDelegate>
{
    IBOutlet NSImageView *qrImageView;
    IBOutlet NSTextField *tipsLabel;
    IBOutlet NSButton *retryButton;
    WXMainWindowController *mainWndController;
    WXAccess *access;
}

@property (assign) IBOutlet NSWindow *window;

@end
