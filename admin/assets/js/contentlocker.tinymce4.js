/*!
* Content Locker
*
* https://mythemeshop.com/plugins/content-locker/
*
* (c) Copyright mythemeshop.com
*
* @author  MyThemeShop
*/

/*global tinymce*/
( function() {
	"use strict";

    tinymce.PluginManager.add( 'contentlocker', function( editor ) {

		var menuCreated = false;
        var menu = [];

        editor.addButton( 'contentlocker', {
            title: 'Content Locker',
            type: 'menubutton',
            icon: 'icon mts-cl-shortcode-icon',
            menu: menu,

            /*
             * After rendeing contol, starts to load manu items (locker shortcodes).
             */
            onpostrender: function() {

				if ( menuCreated ) {
					return;
				}
                menuCreated = true;

				menu.push({
					text: 'Social Lockers',
					value: 'sociallocker',
					onclick: function() {
						editor.windowManager.open({
							title: 'Social Lockers',
		                    body: [
		                        {
									type: 'listbox',
									name: 'post_id',
									label: 'Select the locker you want to use',
									values: window.mts_cl_social_lockers
								}
		                    ],
		                    onsubmit: function(ed) {
		                        editor.focus();
		                        editor.selection.setContent('[sociallocker id=' + ed.data.post_id + ']' + editor.selection.getContent() + '[/sociallocker]');
		                    }
						});
					}
				});

				menu.push({
					text: 'Sign-In Lockers',
					value: 'signinlocker',
					onclick: function() {
						editor.windowManager.open({
							title: 'Sign-In Lockers',
		                    body: [
		                        {
									type: 'listbox',
									name: 'post_id',
									label: 'Select the locker you want to use',
									values: window.mts_cl_signin_lockers
								}
		                    ],
		                    onsubmit: function(ed) {
		                        editor.focus();
		                        editor.selection.setContent('[signinlocker id=' + ed.data.post_id + ']' + editor.selection.getContent() + '[/signinlocker]');
		                    }
						});
					}
				});
            }
        });
    });
} )();
