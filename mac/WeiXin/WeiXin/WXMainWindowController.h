//
//  WXMainWindowController.h
//  WeiXin
//
//  Created by TanHao on 13-8-26.
//  Copyright (c) 2013年 http://www.tanhao.me. All rights reserved.
//

#import <Cocoa/Cocoa.h>

@class WXAccess;
@interface WXMainWindowController : NSWindowController
{
    IBOutlet NSOutlineView *friendsList;
    IBOutlet NSView *messagePanel;
    IBOutlet NSTextView *messageView;
    IBOutlet NSTextField *messageField;
    
    IBOutlet NSTextField *stateFild;
    IBOutlet NSButton *retryButton;
}
@property (nonatomic, strong) WXAccess *access;

@end
