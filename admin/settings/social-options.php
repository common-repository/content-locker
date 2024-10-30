<?php
/**
 * The file contains common settings for the plugin.
 */

$cmb->add_field( array(
	'name' => '<i class="fa fa-user-circle-o"></i>' . esc_html__( 'Social Options', 'content-locker' ),
	'id' => 'settings-social-tab',
	'type' => 'section',
	'render_row_cb' => 'cl_cmb_tab_open_tag'
) );

	$cmb->add_field( array(
		'id' => 'settings-social-hint',
		'desc' => esc_html__( 'Enable Social Sign-In buttons by adding API keys and app IDs for social media sites.', 'content-locker' ),
		'type' => 'title',
		'render_row_cb' => 'cl_cmb_alert'
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Button Language', 'content-locker' ),
		'id' => 'lang',
		'type' => 'select',
		'desc' => esc_html__( 'Select the language that will be used for the social buttons in Social Lockers.', 'content-locker' ),
		'options' => array(
			'ca_ES' => esc_html__( 'Catalan', 'content-locker' ),
			'cs_CZ' => esc_html__( 'Czech', 'content-locker' ),
			'cy_GB' => esc_html__( 'Welsh', 'content-locker' ),
			'da_DK' => esc_html__( 'Danish', 'content-locker' ),
			'de_DE' => esc_html__( 'German', 'content-locker' ),
			'eu_ES' => esc_html__( 'Basque', 'content-locker' ),
			'en_US' => esc_html__( 'English', 'content-locker' ),
			'es_ES' => esc_html__( 'Spanish', 'content-locker' ),
			'fi_FI' => esc_html__( 'Finnish', 'content-locker' ),
			'fr_FR' => esc_html__( 'French', 'content-locker' ),
			'gl_ES' => esc_html__( 'Galician', 'content-locker' ),
			'hu_HU' => esc_html__( 'Hungarian', 'content-locker' ),
			'it_IT' => esc_html__( 'Italian', 'content-locker' ),
			'ja_JP' => esc_html__( 'Japanese', 'content-locker' ),
			'ko_KR' => esc_html__( 'Korean', 'content-locker' ),
			'nb_NO' => esc_html__( 'Norwegian', 'content-locker' ),
			'nl_NL' => esc_html__( 'Dutch', 'content-locker' ),
			'pl_PL' => esc_html__( 'Polish', 'content-locker' ),
			'pt_BR' => esc_html__( 'Portuguese (Brazil)', 'content-locker' ),
			'pt_PT' => esc_html__( 'Portuguese (Portugal)', 'content-locker' ),
			'ro_RO' => esc_html__( 'Romanian', 'content-locker' ),
			'ru_RU' => esc_html__( 'Russian', 'content-locker' ),
			'sk_SK' => esc_html__( 'Slovak', 'content-locker' ),
			'sl_SI' => esc_html__( 'Slovenian', 'content-locker' ),
			'sv_SE' => esc_html__( 'Swedish', 'content-locker' ),
			'th_TH' => esc_html__( 'Thai', 'content-locker' ),
			'tr_TR' => esc_html__( 'Turkish', 'content-locker' ),
			'ku_TR' => esc_html__( 'Kurdish', 'content-locker' ),
			'zh_CN' => esc_html__( 'Simplified Chinese (China)', 'content-locker' ),
			'zh_HK' => esc_html__( 'Traditional Chinese (Hong Kong)', 'content-locker' ),
			'zh_TW' => esc_html__( 'Traditional Chinese (Taiwan)', 'content-locker' ),
			'af_ZA' => esc_html__( 'Afrikaans', 'content-locker' ),
			'sq_AL' => esc_html__( 'Albanian', 'content-locker' ),
			'hy_AM' => esc_html__( 'Armenian', 'content-locker' ),
			'az_AZ' => esc_html__( 'Azeri', 'content-locker' ),
			'be_BY' => esc_html__( 'Belarusian', 'content-locker' ),
			'bn_IN' => esc_html__( 'Bengali', 'content-locker' ),
			'bs_BA' => esc_html__( 'Bosnian', 'content-locker' ),
			'bg_BG' => esc_html__( 'Bulgarian', 'content-locker' ),
			'hr_HR' => esc_html__( 'Croatian', 'content-locker' ),
			'nl_BE' => esc_html__( 'Dutch (Belgie)', 'content-locker' ),
			'eo_EO' => esc_html__( 'Esperanto', 'content-locker' ),
			'et_EE' => esc_html__( 'Estonian', 'content-locker' ),
			'fo_FO' => esc_html__( 'Faroese', 'content-locker' ),
			'ka_GE' => esc_html__( 'Georgian', 'content-locker' ),
			'el_GR' => esc_html__( 'Greek', 'content-locker' ),
			'gu_IN' => esc_html__( 'Gujarati', 'content-locker' ),
			'hi_IN' => esc_html__( 'Hindi', 'content-locker' ),
			'is_IS' => esc_html__( 'Icelandic', 'content-locker' ),
			'id_ID' => esc_html__( 'Indonesian', 'content-locker' ),
			'ga_IE' => esc_html__( 'Irish', 'content-locker' ),
			'jv_ID' => esc_html__( 'Javanese', 'content-locker' ),
			'kn_IN' => esc_html__( 'Kannada', 'content-locker' ),
			'kk_KZ' => esc_html__( 'Kazakh', 'content-locker' ),
			'la_VA' => esc_html__( 'Latin', 'content-locker' ),
			'lv_LV' => esc_html__( 'Latvian', 'content-locker' ),
			'li_NL' => esc_html__( 'Limburgish', 'content-locker' ),
			'lt_LT' => esc_html__( 'Lithuanian', 'content-locker' ),
			'mk_MK' => esc_html__( 'Macedonian', 'content-locker' ),
			'mg_MG' => esc_html__( 'Malagasy', 'content-locker' ),
			'ms_MY' => esc_html__( 'Malay', 'content-locker' ),
			'mt_MT' => esc_html__( 'Maltese', 'content-locker' ),
			'mr_IN' => esc_html__( 'Marathi', 'content-locker' ),
			'mn_MN' => esc_html__( 'Mongolian', 'content-locker' ),
			'ne_NP' => esc_html__( 'Nepali', 'content-locker' ),
			'pa_IN' => esc_html__( 'Punjabi', 'content-locker' ),
			'rm_CH' => esc_html__( 'Romansh', 'content-locker' ),
			'sa_IN' => esc_html__( 'Sanskrit', 'content-locker' ),
			'sr_RS' => esc_html__( 'Serbian', 'content-locker' ),
			'so_SO' => esc_html__( 'Somali', 'content-locker' ),
			'sw_KE' => esc_html__( 'Swahili', 'content-locker' ),
			'tl_PH' => esc_html__( 'Filipino', 'content-locker' ),
			'ta_IN' => esc_html__( 'Tamil', 'content-locker' ),
			'tt_RU' => esc_html__( 'Tatar', 'content-locker' ),
			'te_IN' => esc_html__( 'Telugu', 'content-locker' ),
			'ml_IN' => esc_html__( 'Malayalam', 'content-locker' ),
			'uk_UA' => esc_html__( 'Ukrainian', 'content-locker' ),
			'uz_UZ' => esc_html__( 'Uzbek', 'content-locker' ),
			'vi_VN' => esc_html__( 'Vietnamese', 'content-locker' ),
			'xh_ZA' => esc_html__( 'Xhosa', 'content-locker' ),
			'zu_ZA' => esc_html__( 'Zulu', 'content-locker' ),
			'km_KH' => esc_html__( 'Khmer', 'content-locker' ),
			'tg_TJ' => esc_html__( 'Tajik', 'content-locker' ),
			'ar_AR' => esc_html__( 'Arabic', 'content-locker' ),
			'he_IL' => esc_html__( 'Hebrew', 'content-locker' ),
			'ur_PK' => esc_html__( 'Urdu', 'content-locker' ),
			'fa_IR' => esc_html__( 'Persian', 'content-locker' ),
			'sy_SY' => esc_html__( 'Syriac', 'content-locker' ),
			'yi_DE' => esc_html__( 'Yiddish', 'content-locker' ),
			'gn_PY' => esc_html__( 'Guarani', 'content-locker' ),
			'qu_PE' => esc_html__( 'Quechua', 'content-locker' ),
			'ay_BO' => esc_html__( 'Aymara', 'content-locker' ),
			'se_NO' => esc_html__( 'Northern Sami', 'content-locker' ),
			'ps_AF' => esc_html__( 'Pashto', 'content-locker' )
		),
		'default' => 'en_US'
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Lazy Loading', 'content-locker' ),
		'id' => 'lazyload',
		'type' => 'radio_inline',
		'desc' => esc_html__( 'If on, creates social buttons only at the moment when the locker gets visible on the screen (for better performance).', 'content-locker' ),
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' ),
		),
		'default' => 'off'
	) );

	$url = cl_get_help_url('creating-facebook-app');
	$cmb->add_field( array(
		'name' => esc_html__( 'Facebook Sign-In Button', 'content-locker' ),
		'id' => 'settings-social-facebok-hint',
		'type' => 'hint',
		'desc' => wp_kses_post( sprintf( __( 'The Facebook Sign-In button requires a Facebook App ID, you need to <a href="%s" target="_blank">create an app</a> for your website. Also by creating your own Facebook App you will be able to change the title, description and image for the "Sign In" popup window.', 'content-locker' ), $url ) ),
		'render_row_cb' => 'cl_cmb_alert'
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Facebook App ID', 'content-locker' ),
		'id' => 'facebook_appid',
		'type' => 'text',
		'classes' => 'no-border',
		'desc' => wp_kses_post( sprintf( __( 'The Facebook App ID of your Facebook app.', 'content-locker' ), $url ) ),
		'default' => ''
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Facebook API Version', 'content-locker' ),
		'id' => 'facebook_version',
		'type' => 'radio_inline',
		'desc' => esc_html__( 'Optional, use the most recent version of the API (v2.6) but if Facebook buttons or widgets don\'t work on your website try to switch to other versions.', 'content-locker' ),
		'options' => array(
			'v2.0' => esc_html__( 'v2.0', 'content-locker' ),
			'v2.3' => esc_html__( 'v2.3', 'content-locker' ),
			'v2.4' => esc_html__( 'v2.4', 'content-locker' ),
			'v2.5' => esc_html__( 'v2.5', 'content-locker' ),
			'v2.6' => esc_html__( 'v2.6', 'content-locker' )
		),
		'default' => 'v2.6'
	) );

	$url = cl_get_help_url('creating-twitter-app');
	$cmb->add_field( array(
		'name' => esc_html__( 'Twitter Sign-In Button', 'content-locker' ),
		'id' => 'settings-social-twitter-hint',
		'type' => 'hint',
		'desc' => wp_kses_post( sprintf( __( 'The Twitter Sign-In button requires a Twitter App, please <a href="%s" target="_blank">create an app</a>. Also by creating your own Twitter app you will be able to change the title, description and image for the "Sign In" popup window.', 'content-locker' ), $url ) ),
		'render_row_cb' => 'cl_cmb_alert'
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Twitter Consumer Key', 'content-locker' ),
		'id' => 'twitter_consumer_key',
		'type' => 'text',
		'classes' => 'no-border',
		'desc' => esc_html__( 'The Twitter Consumer Key of your Twitter App.', 'content-locker' ),
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Twitter Consumer Secret', 'content-locker' ),
		'id' => 'twitter_consumer_secret',
		'type' => 'text',
		'desc' => esc_html__( 'The Twitter Consumer Secret of your Twitter App.', 'content-locker' ),
	) );

	$url = cl_get_help_url('creating-google-app');
	$cmb->add_field( array(
		'name' => esc_html__( 'Google Sign-In Button', 'content-locker' ),
		'id' => 'settings-social-google-hint',
		'type' => 'hint',
		'desc' => wp_kses_post( sprintf( __( 'The Google Sign-In button requires a Google Client ID, you need to <a href="%s" target="_blank">create a Client ID</a> for your website.', 'content-locker' ), $url ) ),
		'render_row_cb' => 'cl_cmb_alert'
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Google Client ID', 'content-locker' ),
		'id' => 'google_client_id',
		'type' => 'text',
		'desc' => wp_kses_post( sprintf( __( 'The Google Client ID of your Goole App.', 'content-locker' ), $url ) ),
	) );

	$url = cl_get_help_url('creating-linkedin-app');
	$cmb->add_field( array(
		'name' => esc_html__( 'LinkedIn Sign-In Button', 'content-locker' ),
		'id' => 'settings-social-linkedin-hint',
		'type' => 'hint',
		'desc' => wp_kses_post( sprintf( __( 'The LinkedIn Sign-In button requires a LinkedIn App, you need to <a href="%s" target="_blank">create an app</a> for your website.', 'content-locker' ), $url ) ),
		'render_row_cb' => 'cl_cmb_alert'
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'LinkedIn Client ID', 'content-locker' ),
		'id' => 'linkedin_client_id',
		'type' => 'text',
		'classes' => 'no-border',
		'desc' => wp_kses_post( sprintf( __( 'The Linkedin Client ID of your LinkedIn App.', 'content-locker' ), $url ) ),
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'LinkedIn Client Secret', 'content-locker' ),
		'id' => 'linkedin_client_secret',
		'type' => 'text',
		'desc' => esc_html__( 'The LinkedIn Client Secret of your LinkedIn App.', 'content-locker' ),
	) );

$cmb->add_field( array(
	'id' => 'settings-social-tab-close',
	'type' => 'section_end',
	'render_row_cb' => 'cl_cmb_tab_close_tag'
) );
