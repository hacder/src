//
//  WXMainWindowController.m
//  WeiXin
//
//  Created by TanHao on 13-8-26.
//  Copyright (c) 2013年 http://www.tanhao.me. All rights reserved.
//

#import "WXMainWindowController.h"
#import "WXCoreHelper.h"
#import "WXAccess.h"

@interface WXMainWindowController ()
{
    NSArray *friends;
    NSMutableDictionary *messageInfo;
    
    BOOL serviceOK;
}
@end

@implementation WXMainWindowController
@synthesize access;

- (id)init
{
    self = [super initWithWindowNibName:@"WXMainWindowController"];
    if (self) {
        messageInfo = [NSMutableDictionary dictionary];
    }
    return self;
}

- (void)windowDidLoad
{
    [super windowDidLoad];
    
    dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_DEFAULT, 0), ^{
        
        NSMutableArray *friendsArray = [NSMutableArray array];
        
        //获得基础的信息
        NSDictionary *wxInfo = [WXCoreHelper wxInit:access];
        access.owner = [wxInfo objectForKey:@"User"];
        access.sKey = [wxInfo objectForKey:@"SKey"];
        access.syncKey = [[wxInfo objectForKey:@"SyncKey"] objectForKey:@"List"];
        NSArray *publicFriends = [wxInfo objectForKey:@"ContactList"];
        [friendsArray addObjectsFromArray:publicFriends];
        friends = [NSArray arrayWithArray:friendsArray];
        dispatch_sync(dispatch_get_main_queue(), ^{
            [self refreshOwner];
            [friendsList reloadData];
        });
        
        //与服务器同步
        [WXCoreHelper receiveMessage:access];
        
        //获得所有的联系人
        NSDictionary *contactInfo = [WXCoreHelper friends:access];
        NSArray *privateFriends = [contactInfo objectForKey:@"MemberList"];
        [friendsArray addObjectsFromArray:privateFriends];
        friends = [NSArray arrayWithArray:friendsArray];
        
        dispatch_async(dispatch_get_main_queue(), ^{
            [friendsList reloadData];
        });
        
        [WXCoreHelper webwxstatusnotify:access];
        
        //保持在线状态,并轮询信息
        while (access)
        {
            int state = [WXCoreHelper synccheck:access];
            NSLog(@"state:%d",state);
            
            if (state <-1 || state>6)
            {
                serviceOK = NO;
                dispatch_async(dispatch_get_main_queue(), ^{
                    [self serviceFaild];
                });
                break;
            }
            
            if (state != 0)
            {
                //刷新SyncKey
                NSArray *newMessages = [WXCoreHelper receiveMessage:access];
                NSLog(@"meaage:%@",newMessages);
                for (NSDictionary *aMsg in newMessages)
                {
                    NSString *userName = [aMsg objectForKey:@"FromUserName"];
                    NSMutableArray *cuMsg = [messageInfo objectForKey:userName];
                    if (!cuMsg) {
                        cuMsg = [NSMutableArray arrayWithObject:aMsg];
                        [messageInfo setObject:cuMsg forKey:userName];
                    }else {
                        [cuMsg addObject:aMsg];
                    }
                }
                
                //刷新消息
                dispatch_async(dispatch_get_main_queue(), ^{
                    [self refreshMessageShow];
                });
                
                continue;
            }
            
            serviceOK = YES;
            dispatch_async(dispatch_get_main_queue(), ^{
                [stateFild setHidden:YES];
                [self refreshMessageView];
            });
            sleep(2);
        }
    });
}

- (void)serviceFaild
{
    [messagePanel setHidden:YES];
    [retryButton setHidden:NO];
    [stateFild setHidden:NO];
    [stateFild setStringValue:@"服务器返回异常！"];
}

- (void)refreshOwner
{
    [self.window setTitle:[access.owner objectForKey:@"NickName"]];
}

- (void)refreshMessageView
{
    if ([friendsList selectedRow] != -1 && serviceOK)
    {
        [messagePanel setHidden:NO];
        [self refreshMessageShow];
    }else
    {
        [messagePanel setHidden:YES];
    }
}

- (void)refreshMessageShow
{
    NSMutableString *showMessage = [NSMutableString stringWithString:@""];
    NSInteger idx = [friendsList selectedRow];
    if (idx != -1)
    {
        NSDictionary *userInfo = [friends objectAtIndex:idx];
        NSString *userName = [userInfo objectForKey:@"UserName"];
        
        NSArray *cuMsg = [messageInfo objectForKey:userName];
        for (id aMsg in cuMsg)
        {
            if ([aMsg isKindOfClass:[NSString class]])
            {
                [showMessage appendFormat:@"我说:%@\n",aMsg];
            }else
            {
                [showMessage appendFormat:@"%@说:%@\n",[userInfo objectForKey:@"NickName"],[(NSDictionary*)aMsg objectForKey:@"Content"]];
            }
        }
    }
    [messageView setString:showMessage];
}

- (IBAction)retryClick:(id)sender
{
    [self.window orderOut:nil];
    [[NSNotificationCenter defaultCenter] postNotificationName:@"WXLoginNotification" object:nil userInfo:NULL];
}

- (IBAction)messageSend:(id)sender
{
    NSInteger idx = [friendsList selectedRow];
    if (idx != -1 && [messageField stringValue].length>0)
    {
        NSString *aMsg = [messageField stringValue];
        NSDictionary *userInfo = [friends objectAtIndex:idx];
        NSString *userName = [userInfo objectForKey:@"UserName"];
        
        NSMutableArray *cuMsg = [messageInfo objectForKey:userName];
        if (!cuMsg) {
            cuMsg = [NSMutableArray arrayWithObject:aMsg];
            [messageInfo setObject:cuMsg forKey:userName];
        }else {
            [cuMsg addObject:aMsg];
        }
        
        [messageField setStringValue:@""];
        [self refreshMessageShow];
        
        dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_DEFAULT, 0), ^{
            BOOL state = [WXCoreHelper sendMeaage:access toUser:userName message:aMsg];
            NSLog(@"send state:%d",state);
        });
    }
}

#pragma mark -
#pragma mark NSOutlineViewDataSource

- (NSInteger)outlineView:(NSOutlineView *)outlineView numberOfChildrenOfItem:(id)item
{
    if (!item)
    {
        return [friends count];
    }
    return 0;
}

- (id)outlineView:(NSOutlineView *)outlineView child:(NSInteger)index ofItem:(id)item
{
    if (!item)
    {
        return [friends objectAtIndex:index];
    }
    return nil;
}

- (BOOL)outlineView:(NSOutlineView *)outlineView isItemExpandable:(id)item
{
    return NO;
}

- (id)outlineView:(NSOutlineView *)outlineView objectValueForTableColumn:(NSTableColumn *)tableColumn byItem:(id)item
{
    return @"";
}

#pragma mark -
#pragma mark NSOutlineViewDelegate

- (void)outlineView:(NSOutlineView *)outlineView willDisplayCell:(id)cell forTableColumn:(NSTableColumn *)tableColumn item:(id)item
{
    NSString *nickName = [item objectForKey:@"NickName"];
    [cell setStringValue:nickName];
}

- (BOOL)outlineView:(NSOutlineView *)outlineView shouldEditTableColumn:(NSTableColumn *)tableColumn item:(id)item
{
    return NO;
}

- (void)outlineViewSelectionDidChange:(NSNotification *)notification
{
    [self refreshMessageView];
}

@end
