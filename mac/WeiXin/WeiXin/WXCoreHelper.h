//
//  WXLoginHelper.h
//  WeiXin
//
//  Created by TanHao on 13-8-25.
//  Copyright (c) 2013年 http://www.tanhao.me. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "WXAccess.h"

@interface WXCoreHelper : NSObject

//由微信服务器返回一个会话ID
+ (NSString *)wxUUID;

//获取登录的二维码
+ (NSImage *)qrCode:(NSString *)uuid;

//获取登录的页面地址
+ (NSString *)loginPage:(NSString *)uuid state:(int *)state;

//通过登录页登录
+ (WXAccess *)login:(NSString *)loginPage;

//此步骤不明情况
+ (void)webwxstatreport:(NSString *)uuid;

//微信初使化
+ (NSDictionary *)wxInit:(WXAccess *)access;

//获取好友列表
+ (NSDictionary *)friends:(WXAccess *)access;

//此步骤不明情况(给自己发了一条消息)
+ (BOOL)webwxstatusnotify:(WXAccess *)access;

//保持与服务器的同步
+ (int)synccheck:(WXAccess *)access;

//接收消息
+ (NSArray *)receiveMessage:(WXAccess *)access;

//发送消息
+ (BOOL)sendMeaage:(WXAccess *)access toUser:(NSString *)user message:(NSString *)message;

@end
