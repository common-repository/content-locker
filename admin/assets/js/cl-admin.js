/*!
* Content Locker
*
* https://mythemeshop.com/plugins/content-locker/
*
* (c) Copyright mythemeshop.com
*
* @version 1.0.0
* @author  MyThemeShop
*/

/*global Clipboard,mts_cl,confirm*/
;(function($) {

	"use strict";

	var ContentLocker = {

		init: function() {

			this.tabs();
			this.sortable();
			this.extraOptins();
			this.serviceChecker();
			this.passcode();
			this.clearStats();
			this.serviceLists();
			this.themePreviewer();

			// Clipboard
			var post_edit = new Clipboard('.copy-shortcode-field', {
				target: function(trigger) {
					return trigger.previousElementSibling;
				}
			});

			post_edit.on( 'success', function(e){

				var btn = $(e.trigger);
				btn.addClass('dashicons-yes');
				setTimeout(function(){
					btn.removeClass('dashicons-yes');
				}, 800);
				e.clearSelection();
			});

			var post_list = new Clipboard('.copy-list-shortcode-field', {
				target: function(trigger) {
					return trigger.previousElementSibling;
				}
			});
			post_list.on( 'success', function(e){

				var btn = $(e.trigger);
				btn.addClass('dashicons-yes');
				setTimeout(function(){
					btn.removeClass('dashicons-yes');
				}, 800);
				e.clearSelection();
			});

			// Save Last Tab
			$('.wrap-cl-settings > .cmb-form > .button-primary').on('click', function(){
				var btn = $(this),
					link = btn.parent().find('.cl-cmb-tabs-menu a.active');

				localStorage.setItem( '_mts_cl_setting_active_tab', link.attr('href') );
			});

			var item = localStorage.getItem( '_mts_cl_setting_active_tab' );
			if( item ) {
				$('a[href="'+item+'"]').trigger('click');
				localStorage.removeItem( '_mts_cl_setting_active_tab' );
			}

			// Disable Aweber
			$('option:last', '#mailing_0_mailing').attr('disabled', 'disabled');
		},

		themePreviewer: function() {
			// Theme Preview
			var select = $('select', '.cl-theme-preview'),
				default_text = select.val() ? select.val() : 'Select Default',
				markup = $('<div class="dropdown"><span class="dropdown-button">'+ default_text +'</span><ul class="dropdown-list" style="display: none;"></ul></div>');

			select.hide();
			select.after(markup);

			var button = markup.find('.dropdown-button'),
				list = markup.find('.dropdown-list');

			select.find('option').each(function() {
				var opt = $(this),
					val = opt.attr('value');
				list.append('<li data-value="'+ val +'">'+ opt.html() +'<img src="'+ mts_cl.assets + '/img/theme-' + val + '.png"></li>');
			});

			button.on('click', function () {
        		list.toggle();
        		return button.toggleClass('active');
    		});

			list.children().on('click', function () {
				var li = $(this);

		        button.html(li.text());
				select.val(li.data('value'));
				list.toggle();

		        return button.toggleClass('active');
		    });

			list.find('li[data-value=""]').trigger('click');
			if( select.is(':disabled') ) {
				markup.addClass('disabled')
			}
		},

		serviceLists: function() {

			$('.service-lists').each(function(){
				var service = $(this),
					btn = $('<button type="button" class="button">Get List</button>'),
					field = service.prevAll('.service-api-key').find('input, textarea');

				service.find('.cmb-td').append(btn);

				var select = btn.prev('select');

				btn.on('click', function() {

					if( field.length > 0 && field.val().length < 1 ) {
						var notice = $('<div class="service-api-error">Enter api key.</div>');

						btn.after(notice);

						setTimeout(function(){
							notice.fadeOut( 500, function(){
								notice.remove();
							});
						}, 2000 );

						return false;
					}

					var args = {};
					field.each(function(){
						var f = $(this);
						args[f.data('api-id')] = f.val();
					});

					$.ajax({
						url: ajaxurl,
						method: 'post',
						data: {
							action: 'mts-cl-get-service-list',
							service: select.data('service'),
							args: args
						},

						success: function( response ) {

							if( response.success && response.lists ) {
								var sel = select.val();
								select.html( '<option value="none">Select List</option>' );
								$.each( response.lists, function( key, val ){
									select.append('<option value="'+ key +'">'+ val +'</option>');
								});
								select.val(sel);
							}
							else {
								console.log( response.error );
							}

						}
					});

					return false;
				});
			});

			$('.service-api-key input[data-api-url]').each(function(){

				var $this = $(this),
					url = $this.data('api-url');

				if( url ) {
					$this.after('<a href="'+url+'" class="button" target="_blank">Get API Key</a>');
				}

			});
		},

		clearStats: function() {

			$('.cl-clear-data').click(function() {

				var $this = $(this),
					loader = $('<span class="cl-loader"></span>');

				if( true === confirm( 'Are you sure that you want to clear the current statistical data?' ) ) {
					$.ajax({
						url: ajaxurl + '?action=mts-cl-clear-stats-data',
						beforeSend: function() {

							$this.after(loader);
						},
						error: function() {
							loader.remove();
						},
						success: function() {
							loader.remove();
						}
					});
				}

				return false;
			});
		},

		passcode: function() {

			var field = $('.cmb2-id-passcode').find('#passcode');

			if( !field.length ) {
				return;
			}

			var url = $('.cmb2-id-passcode').find('.passcode-example');

			field.on( 'keyup', function() {
				var value = $.trim( field.val() ),
					href = url.data('url') + '?' + value;

				url.html( '<a href="'+ href +'">'+ href +'</a>' ) ;

			}).trigger('keyup');
		},

		sortable: function() {

			var tabs = $('.cl-cmb-tabs-menu:not(.manual)');

			tabs.each(function(){
				var tab = $(this);

				if( tab.data('sortable') ) {
					tab.sortable({
						axis: 'y',
						forceHelperSize: true,
						handle: '.ui-drag',
						helper: 'clone',
						placeholder: 'ui-placeholder',
						create: function( event ) {
							var parent = $(event.target),
								field = $('#'+parent.data('id'));

							field.val(parent.sortable('toArray'));
						},
						update: function( event ) {
							var parent = $(event.target),
								field = $('#'+parent.data('id'));

							field.val(parent.sortable('toArray'));
						}
					});
				}
			});
		},

		tabs: function() {

			// Option Tabs
			var opt = $('.cl-main-tabs'),
				prev_opt = null;
			$('#normal-sortables').before(opt);

			opt.find('a').each(function(){
				var link = $(this),
					target = $( link.attr('href') );

				if( target.length > 0 ) {
					target.addClass('cmb-boneless-box');
					target.hide();
				}
				else {
					link.remove();
				}
			});
			opt.show();

			opt.on('click', 'a', function(){

				var link = $(this),
					target = $( link.attr('href') );

				opt.find('a').removeClass('active');
				link.addClass('active');

				if( target.length > 0 ) {

					if( prev_opt ) {
						prev_opt.hide();
					}

					prev_opt = target;
					target.show();
				}

				return false;
			});

			opt.find('a:first').trigger('click');

			// Tabs
			var tabs = $('.cl-cmb-tabs-menu:not(.manual)');
			tabs.on( 'click', 'a', function(){
				var link = $(this),
					target = $( link.attr('href') );

				link.closest('ul').find('a').removeClass('active');
				link.addClass('active');

				if( target.length > 0 ) {
					target.parent().find('> .cmb-type-section').hide();
					target.show();
				}
				else {
					link.next('ul').find('a:eq(0)').trigger('click');
				}

				return false;
			});

			tabs.each(function(){
				$(this).find('a:eq(0)').trigger('click');
			});

			// Radio Tabs
			var radio = $('.cl-radio-tabs');

			radio.on( 'click', 'label', function(){
				var link = $(this),
					parent = link.closest('.cmb-type-radio-tabs'),
					target;

				parent.find('> .cmb-type-section').hide();

				if( parent.hasClass('cmb-repeat-group-field') ) {
					var grouping = parent.closest('.cmb-repeatable-grouping');
					target = '_' + grouping.data('iterator') + '_' + link.prev().val();
					target = grouping.parent().data('groupid') + target;
					target = $('#section-' + target);
				}
				else {
					target = $( '#section-' + link.prev().val() );
				}
				target.show();
			});

			radio.each(function(){
				$(this).find('input:checked + label').trigger('click');
			});

			// Select Tabs
			$(document).on('change', '.cl-select-tabs select', function(){
				var sel = $(this),
					val = sel.val(),
					parent = sel.closest('.cmb-type-select-tabs'),
					target;

				parent.find('> .cmb-type-section').hide();

				if( parent.hasClass('cmb-repeat-group-field') ) {
					var grouping = parent.closest('.cmb-repeatable-grouping');
					target = '_' + grouping.data('iterator') + '_' + val;
					target = grouping.parent().data('groupid') + target;
					target = $('#section-' + target);
				}
				else {
					target = $( '#section-' + val );
				}
				target.show();
			});

			$('.cl-select-tabs').find('select').trigger('change');

			$(document).on( 'keyup', '.repeating-group-title input', function(){
				var input = $(this);

				if( '' !== input.val() ) {
					input.closest('.cmb-repeatable-grouping').find('.cmb-group-title span').html(input.val());
				}
			});

			$('.repeating-group-title input').trigger('keyup');
		},

		extraOptins: function() {

			$('.cmb-type-more').on( 'click', '.more-link-show, .more-link-hide', function(){
				var button = $(this);

				if( button.hasClass('more-link-show')) {
					button.fadeOut( 'fast', function(){
						button.next().fadeIn(400);
						button.next().next().fadeIn(400, function(){
							$(this).css( 'display', 'block');
						});
					});
				}
				else if( button.hasClass('more-link-hide')) {
					button.next().fadeOut(400);
					button.fadeOut( 400, function(){
						button.prev().fadeIn();
					});
				}

				return false;
			});
		},

		serviceChecker: function() {

			$('.term-checker').on( 'change', 'input', function(){
				var available = $(this),
					content = $('#'+available.data('content')),
					pages = $('#'+available.data('pages'));

				if( 'on' === available.val() ) {
					content.hide();
					pages.show();
				}
				else {
					content.show();
					pages.hide();
				}
			});
			$('.term-checker input:checked').trigger('change');

			$('.service-checker.move-to-parent').each(function(){
				var parent = $(this),
					id = parent.find('[data-for]:eq(0)').data('for');

				parent.append($('#'+id));
			});

			$('.service-checker').on( 'change', 'input', function(){
				var available = $(this),
					target = $('#'+available.data('for')),
					check = target.hasClass('inverse') ? 'off' : 'on';

				if( check === available.val() ) {
					target.addClass('service-active active');
				}
				else {
					target.removeClass('service-active active');
				}
			});

			$('.service-checker input:checked').trigger('change');

			// Overlap mode
			var overlap = $('.overlap-mode-changer');
			if( overlap.length > 0 ) {
				var overlap_mode = overlap.prev().find('.cmb2-radio-list.cmb2-list'),
					select = overlap.find('select');

				select.css({
					'display' : 'none',
					'margin' : '0 0 0 20px'
				});
				overlap_mode.after(select);
				overlap.remove();

				overlap_mode.on( 'change', 'input', function(){
					var input = $(this);

					if( 'full' === input.val() ) {
						select.hide();
					}
					else {
						select.css({
							'display' : 'inline-block'
						});
					}
				});

				overlap_mode.find('input:checked').trigger('change');
			}
		},

		MetaBoxes: {
			init: function() {
				this.manualLock();
			},

			manualLock: function() {
				$('.mts-cl-shortcode').click(function(){
					$(this).select();
				});
			}
		}
	};

	// Init
	$(document).ready(function(){
		ContentLocker.init();
		ContentLocker.MetaBoxes.init();
	});

})(jQuery);
