/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'zh-cn';
	config.skin = 'kama';
	config.width = 600;
	config.height = 200;
	config.toolbarCanCollapse = true;
	config.toolbar_Full = [
       ['Source','-','Save','NewPage','Preview'],
       ['Cut','Copy','Paste'],
       ['TextColor','BGColor'],
       ['Image','Table',],
       ['Styles','Format','Font','FontSize'],
    ];
	config.disableNativeSpellChecker = false ;
	config.scayt_autoStartup = false ;
};
