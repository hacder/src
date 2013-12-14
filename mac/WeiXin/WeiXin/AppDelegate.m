//
//  AppDelegate.m
//  WeiXin
//
//  Created by TanHao on 13-8-25.
//  Copyright (c) 2013年 http://www.tanhao.me. All rights reserved.
//

#import "AppDelegate.h"
#import "THWebService.h"
#import "WXCoreHelper.h"
#import "WXMainWindowController.h"

@interface AppDelegate ()<NSAnimationDelegate>
{
    NSViewAnimation *animation;
}
@end

@implementation AppDelegate

- (void)applicationDidFinishLaunching:(NSNotification *)aNotification
{
    [self loginWX:nil];
    [self.window setMaxSize:self.window.frame.size];
    [self.window setMinSize:self.window.frame.size];
    
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(loginWX:)
                                                 name:@"WXLoginNotification"
                                               object:nil];
}

- (void)loginSucess
{
    NSRect endFrame = self.window.frame;
    endFrame.origin.y += NSHeight(endFrame);
    NSDictionary *animationInfo = @{NSViewAnimationTargetKey : self.window,NSViewAnimationEndFrameKey:[NSValue valueWithRect:endFrame],NSViewAnimationEffectKey:NSViewAnimationFadeOutEffect};
    animation = [[NSViewAnimation alloc] initWithViewAnimations:@[animationInfo]];
    animation.delegate = self;
    [animation setDuration:0.5];
    [animation setAnimationBlockingMode:NSAnimationBlocking];
    [animation startAnimation];
    return;
    
    NSLog(@"%@",[WXCoreHelper wxInit:access]);
    NSLog(@"%@",[WXCoreHelper friends:access]);
}

#pragma mark -
#pragma mark NSAnimationDelegate

- (void)animationDidStop:(NSAnimation*)value
{
    [self animationDidEnd:value];
}

- (void)animationDidEnd:(NSAnimation*)value
{
    [self.window orderOut:nil];
    mainWndController = [[WXMainWindowController alloc] init];
    mainWndController.access = access;
    [mainWndController.window orderFront:nil];
}

#pragma mark -
#pragma mark IBActions

- (IBAction)loginWX:(id)sender
{
    mainWndController.access = nil;
    mainWndController = nil;
    
    [self.window center];
    [self.window orderFront:nil];
    [retryButton setHidden:YES];
    [qrImageView setImage:[NSImage imageNamed:@"weixin"]];
    [tipsLabel setStringValue:@"请等待服务器响应..."];
    
    access = nil;
    dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_DEFAULT, 0), ^{
        //获得会话ID
        NSString *uuid = [WXCoreHelper wxUUID];
        if (uuid)
        {
            //获得二维码
            NSImage *image = [WXCoreHelper qrCode:uuid];
            if (image)
            {
                dispatch_async(dispatch_get_main_queue(), ^{
                    [qrImageView setImage:image];
                    [tipsLabel setStringValue:@"请使用微信手机客户端扫描登录！"];
                });
                
                //轮询服务器登录状态
                while (YES)
                {
                    int state = 0;
                    NSString *loginPage = [WXCoreHelper loginPage:uuid state:&state];
                    if (loginPage)
                    {
                        //最终登录获得uin和sid
                        access = [WXCoreHelper login:loginPage];
                        [WXCoreHelper webwxstatreport:uuid];
                        break;
                    }
                    if (state == 201)
                    {
                        dispatch_async(dispatch_get_main_queue(), ^{
                            [tipsLabel setStringValue:@"成功扫描\n请在手机点击确认以登录！"];
                        });
                    }
                    sleep(1);
                }
            }
        }
        
        if (!access)
        {
            //登录失败
            dispatch_async(dispatch_get_main_queue(), ^{
                [tipsLabel setStringValue:@"与服务器连接失败！"];
                [retryButton setHidden:NO];
            });
        }else
        {
            //登录成功
            dispatch_async(dispatch_get_main_queue(), ^{
                [tipsLabel setStringValue:@"正在登录..."];
                [self loginSucess];
            });
        }
    });
}

@end
