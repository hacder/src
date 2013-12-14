//
//  WXAccess.m
//  WeiXin
//
//  Created by TanHao on 13-8-25.
//  Copyright (c) 2013年 http://www.tanhao.me. All rights reserved.
//

#import "WXAccess.h"

@implementation WXAccess
@synthesize wxuin,wxsid,deviceID,sKey,syncKey,owner;

- (id)init
{
    self = [super init];
    if (self)
    {
        deviceID = [NSString stringWithFormat:@"e%u",arc4random()];
    }
    return self;
}

@end
