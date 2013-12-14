//
//  WXLoginHelper.m
//  WeiXin
//
//  Created by TanHao on 13-8-25.
//  Copyright (c) 2013年 http://www.tanhao.me. All rights reserved.
//

#import "WXCoreHelper.h"
#import "THWebService.h"

@implementation WXCoreHelper

+ (NSString *)timeString
{
    return [NSString stringWithFormat:@"%.0f",[[NSDate date] timeIntervalSince1970]*1000];
}

//由微信服务器返回一个会话ID
+ (NSString *)wxUUID
{
    NSString *urlString = [NSString stringWithFormat:@"https://login.weixin.qq.com/jslogin?appid=wx782c26e4c19acffb&redirect_uri=https%%3A%%2F%%2Fwx.qq.com%%2Fcgi-bin%%2Fmmwebwx-bin%%2Fwebwxnewloginpage&fun=new&lang=zh_CN&_=%@",[self timeString]];
    
    NSData *data = [THWebService dataWithUrl:[NSURL URLWithString:urlString]];
    if (!data)
    {
        return nil;
    }
    NSString *string = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
    NSRange range = [string rangeOfString:@"window.QRLogin.uuid = \""];
    if (range.location != NSNotFound)
    {
        NSString *uuid = [string substringFromIndex:range.location+range.length];
        uuid = [uuid substringToIndex:uuid.length-2];
        return uuid;
    }
    return nil;
}

//获取登录的二维码
+ (NSImage *)qrCode:(NSString *)uuid
{
    NSString *urlString = [NSString stringWithFormat:@"https://login.weixin.qq.com/qrcode/%@?t=webwx",uuid];
    NSData *data = [THWebService dataWithUrl:[NSURL URLWithString:urlString]];
    if (!data)
    {
        return nil;
    }
    NSImage *image = [[NSImage alloc] initWithData:data];
    return image;
}

//获取登录的页面地址
+ (NSString *)loginPage:(NSString *)uuid state:(int *)state
{
    NSString *urlString = [NSString stringWithFormat:@"https://login.weixin.qq.com/cgi-bin/mmwebwx-bin/login?uuid=%@&tip=1&_=%@",uuid,[self timeString]];
    NSData *data = [THWebService dataWithUrl:[NSURL URLWithString:urlString]];
    if (!data)
    {
        return nil;
    }
    NSString *string = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
    
    //已经点击了确认登录
    NSRange range = [string rangeOfString:@"window.redirect_uri=\""];
    if (range.location != NSNotFound)
    {
        NSString *loginPage = [string substringFromIndex:range.location+range.length];
        loginPage = [loginPage substringToIndex:loginPage.length-2];
        return loginPage;
    }
    
    //已经扫描成功
    range = [string rangeOfString:@"window.code=201"];
    if (range.location != NSNotFound)
    {
        if (state) *state = 201;
    }
    return nil;
}

//通过登录页登录
+ (WXAccess *)login:(NSString *)loginPage
{
    NSURL *url = [NSURL URLWithString:loginPage];
    NSURLRequest *request = [NSURLRequest requestWithURL:url];
    NSError *error = NULL;
    [NSURLConnection sendSynchronousRequest:request returningResponse:NULL error:&error];
    if (error)
    {
        return nil;
    }
    
    NSHTTPCookieStorage *cookieStorage = [NSHTTPCookieStorage sharedHTTPCookieStorage];
    WXAccess *access = [[WXAccess alloc] init];
    for (NSHTTPCookie *cookie in [cookieStorage cookies])
    {
        if ([cookie.name isEqualToString:@"wxuin"])
        {
            access.wxuin = [cookie value];
        }
        if ([cookie.name isEqualToString:@"wxsid"])
        {
            access.wxsid = [cookie value];
        }
    }
    if (access.wxuin && access.wxsid)
    {
        return access;
    }
    return nil;
}

+ (void)webwxstatreport:(NSString *)uuid
{
    NSString *urlString = [NSString stringWithFormat:@"https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxstatreport?type=1&r=%@",[self timeString]];
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:urlString]];
    NSString *bodyString = [NSString stringWithFormat:@"{\"BaseRequest\":{\"Uin\":0,\"Sid\":0},\"Count\":1,\"List\":[{\"Type\":1,\"Text\":\"/cgi-bin/mmwebwx-bin/login, Second Request Success, uuid: %@, time: 2896ms\"}]}",uuid];
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:[bodyString dataUsingEncoding:NSUTF8StringEncoding]];
    
    [NSURLConnection sendSynchronousRequest:request returningResponse:NULL error:NULL];
}

//微信初使化
+ (NSArray *)wxInit:(WXAccess *)access
{
    NSString *urlString = [NSString stringWithFormat:@"https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxinit?r=%@",[self timeString]];
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:urlString]];
    NSString *bodyString = [NSString stringWithFormat:@"{\"BaseRequest\":{\"Uin\":\"%@\",\"Sid\":\"%@\",\"Skey\":\"\",\"DeviceID\":\"%@\"}}",access.wxuin,access.wxsid,access.deviceID];
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:[bodyString dataUsingEncoding:NSUTF8StringEncoding]];
    
    NSData *data = [NSURLConnection sendSynchronousRequest:request returningResponse:NULL error:NULL];
    if (!data)
    {
        return nil;
    }
    
    return [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingMutableContainers error:NULL];
}

//获取好友列表
+ (NSArray *)friends:(WXAccess *)access
{
    NSString *urlString = [NSString stringWithFormat:@"https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxgetcontact?r=%@",[self timeString]];
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:urlString]];
    NSString *bodyString = [NSString stringWithFormat:@"{}"];
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:[bodyString dataUsingEncoding:NSUTF8StringEncoding]];
    
    NSData *data = [NSURLConnection sendSynchronousRequest:request returningResponse:NULL error:NULL];
    if (!data)
    {
        return nil;
    }
    
    return [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingMutableContainers error:NULL];
}

+ (BOOL)webwxstatusnotify:(WXAccess *)access
{
    NSString *timeString = [self timeString];
    NSString *urlString = [NSString stringWithFormat:@"https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxsendmsg?sid=%@&r=%@",access.wxsid,timeString];
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:urlString]];
    NSDictionary *bodyInfo =
    @{
    @"BaseRequest" : @{@"Uin":access.wxuin,@"Sid":access.wxsid,@"Skey":access.sKey,@"DeviceID":access.deviceID},
    @"Msg" : @{@"FromUserName":[access.owner objectForKey:@"UserName"],
    @"ToUserName":[access.owner objectForKey:@"UserName"],@"Type":@(3),@"ClientMsgId":timeString}
    };
    
    NSData *bodyData = [NSJSONSerialization dataWithJSONObject:bodyInfo options:0 error:NULL];
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:bodyData];
    
    NSData *data = [NSURLConnection sendSynchronousRequest:request returningResponse:NULL error:NULL];
    if (!data)
    {
        return NO;
    }
    
    NSDictionary *resultInfo = [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingMutableContainers error:NULL];
    int state = [[[resultInfo objectForKey:@"BaseResponse"] objectForKey:@"Ret"] intValue];
    if (state == 0) {
        return YES;
    }
    return NO;
}

//保持与服务器的同步
+ (int)synccheck:(WXAccess *)access
{
    NSMutableString *syncKeyString = [NSMutableString stringWithString:@""];
    for (NSDictionary *key in access.syncKey)
    {
        [syncKeyString appendFormat:@"%@%@_%@",syncKeyString.length>0?@"%7C":@"",
         [key objectForKey:@"Key"],[key objectForKey:@"Val"]];
    }
    
    NSString *urlString = [NSString stringWithFormat:@"https://webpush.weixin.qq.com/cgi-bin/mmwebwx-bin/synccheck?callback=jQuery18309326978388708085_%@&r=%@&sid=%@&uin=%@&deviceid=%@&synckey=%@&_=%@",[self timeString],[self timeString],access.wxsid,access.wxuin,access.deviceID,syncKeyString,[self timeString]];
    
    NSData *data = [NSURLConnection sendSynchronousRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:urlString]]
                                         returningResponse:NULL error:NULL];
    if (!data)
    {
        return -1;
    }
    NSString *string = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
    
    NSRange range = [string rangeOfString:@"retcode:\""];
    if (range.location == NSNotFound) {
        return -1;
    }
    string = [string substringFromIndex:range.location+range.length];
    
    range = [string rangeOfString:@"selector:\""];
    if (range.location == NSNotFound)
    {
        return -1;
    }
    
    NSString *retcodeString = [string substringToIndex:range.location-2];
    int retcode = [retcodeString intValue];
    if (retcode != 0) {
        return -2;
    }
    
    NSString *stateString = [string substringFromIndex:range.location+range.length];
    stateString = [stateString substringToIndex:stateString.length-2];
    return [stateString intValue];
}

+ (NSArray *)receiveMessage:(WXAccess *)access
{
    NSString *urlString = [NSString stringWithFormat:@"https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxsync?sid=S11EXjyZcBEjzlFh&r=%@",[self timeString]];
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:urlString]];
    NSDictionary *bodyInfo =
    @{
    @"BaseRequest" : @{@"Uin":access.wxuin,@"Sid":access.wxsid},
    @"SyncKey" : @{@"Count":@(access.syncKey.count),@"List":access.syncKey},
    @"rr" : [self timeString]
    };
    
    NSData *bodyData = [NSJSONSerialization dataWithJSONObject:bodyInfo options:0 error:NULL];    
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:bodyData];
    
    NSData *data = [NSURLConnection sendSynchronousRequest:request returningResponse:NULL error:NULL];
    if (!data)
    {
        return nil;
    }
    
    NSDictionary *synKeyInfo = [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingMutableContainers error:NULL];
    NSArray *syncKey = [[synKeyInfo objectForKey:@"SyncKey"] objectForKey:@"List"];
    if (syncKey) access.syncKey = syncKey;
    NSString *sKey = [synKeyInfo objectForKey:@"SKey"];
    if (sKey) access.sKey = sKey;
    
    return [synKeyInfo objectForKey:@"AddMsgList"];
}

//发送消息
+ (BOOL)sendMeaage:(WXAccess *)access toUser:(NSString *)user message:(NSString *)message
{
    NSString *timeString = [self timeString];
    NSString *urlString = [NSString stringWithFormat:@"https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxsendmsg?sid=%@&r=%@",access.wxsid,timeString];
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:urlString]];
    NSDictionary *bodyInfo =
    @{
    @"BaseRequest" : @{@"Uin":access.wxuin,@"Sid":access.wxsid,@"Skey":access.sKey,@"DeviceID":access.deviceID},
    @"Msg" : @{@"FromUserName":[access.owner objectForKey:@"UserName"],
               @"ToUserName":user,@"Type":@(1),@"Content":message,@"ClientMsgId":timeString,@"LocalID":timeString},
    @"rr" : [self timeString]
    };
    
    NSData *bodyData = [NSJSONSerialization dataWithJSONObject:bodyInfo options:0 error:NULL];
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:bodyData];
    
    NSData *data = [NSURLConnection sendSynchronousRequest:request returningResponse:NULL error:NULL];
    if (!data)
    {
        return NO;
    }
    
    NSDictionary *resultInfo = [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingMutableContainers error:NULL];
    int state = [[[resultInfo objectForKey:@"BaseResponse"] objectForKey:@"Ret"] intValue];
    if (state == 0) {
        return YES;
    }
    return NO;
}

@end
