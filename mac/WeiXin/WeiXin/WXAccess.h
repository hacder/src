//
//  WXAccess.h
//  WeiXin
//
//  Created by TanHao on 13-8-25.
//  Copyright (c) 2013年 http://www.tanhao.me. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface WXAccess : NSObject
@property (nonatomic, readonly) NSString *deviceID;
@property (nonatomic, strong) NSString *wxuin;
@property (nonatomic, strong) NSString *wxsid;
@property (nonatomic, strong) NSString *sKey;
@property (nonatomic, strong) NSArray *syncKey;
@property (nonatomic, strong) NSDictionary *owner;
@end
