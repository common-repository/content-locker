<?php
/**
 * The file contains common settings for the plugin.
 */

$cmb->add_field( array(
	'name' => '<i class="fa fa-newspaper-o"></i>' . esc_html__( 'Terms &amp; Policies', 'content-locker' ),
	'id' => 'settings-terms-tab',
	'type' => 'section',
	'render_row_cb' => 'cl_cmb_tab_open_tag'
) );

	$cmb->add_field( array(
		'id' => 'settings-terms-hint',
		'desc' => esc_html__( 'Configure here Terms of Use and Privacy Policy for lockers on your website.', 'content-locker' ),
		'type' => 'title',
		'render_row_cb' => 'cl_cmb_alert'
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Enable Policies', 'content-locker' ),
		'id' => 'terms_enabled',
		'type' => 'radio_inline',
		'classes' => 'no-border',
		'desc' => esc_html__( 'Set On to show the links to Terms of Use and Privacy Policy of your website below the Sign-In/Email lockers.', 'content-locker' ),
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' )
		),
		'default' => 'on'
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Use Existing Pages', 'content-locker' ),
		'id' => 'terms_use_pages',
		'type' => 'radio_inline',
		'desc' => esc_html__( 'Set On, if your website already contains pages for "Terms of Use" and "Privacy Policies" and you want to use them.', 'content-locker' ),
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' )
		),
		'default' => 'off',
		'attributes'  => array(
			'data-content' => 'section-term-content',
			'data-pages' => 'section-term-pages'
		),
		'classes' => 'term-checker'
	) );

	$cmb->add_field( array(
		'id' => 'term-content',
		'type' => 'dependency',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Terms of Use', 'content-locker' ),
			'id' => 'terms_of_use_text',
			'type' => 'wysiwyg',
			'classes' => 'no-border',
			'desc' => esc_html__( 'The text of Terms of Use. The link to this text will be shown below the lockers.', 'content-locker' ),
			'options' => array(
				'textarea_rows' => 20
			),
			'default' => '<h2>Terms of Use</h2>
				<p><em>This Terms of Use explains the operation principle of the Content Lockers placed on this website.</em></p>
				<p>On this website, you can encounter the Content Lockers which may ask<br /> you to sign in, subscribe, enter your name or perform other actions to get access to the locked content.</p>
				<h4>Using your email address</h4>
				<p>When you enter your email or sign in through social networks, you agree to that your email address will be added to the subscription list for sending target news, newsletters and special offers.</p>
				<p>You can unsubscribe at any time by clicking on the link at the end of any of emails received from us.</p>
				<h4>Social Apps &amp; Permissions</h4>
				<p>When you sign in through social networks, the Content Locker may ask you to grant permissions to read or perform some social actions.</p>
				<p>The Content Locker retrieves only the following information (according the Privacy Policy of this website):</p>
				<ul>
				<li>Person name</li>
				<li>Email address</li>
				</ul>
				<p>Content Locker never collects other data and never publish anything in social networks from your behalf without your permissions.</p>
				<p>After unlocking the content the Content Locker removes all the access tokens received from you and never uses them again.</p>
				<p>If there are any questions regarding this Terms of Use you may contact us.</p>'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Privacy Policy', 'content-locker' ),
			'id' => 'privacy_policy_text',
			'type' => 'wysiwyg',
			'desc' => esc_html__( 'The text of Privacy Policy. The link to this text will be shown below the lockers.', 'content-locker' ),
			'options' => array(
				'textarea_rows' => 20
			),
			'default' => "<h2>Privacy Policy</h2>
	<p><em>This privacy policy has been compiled to better serve those who are concerned with how their 'Personally identifiable information' (PII) is being used online. PII, as used in US privacy law and information security, is information that can be used on its own or with other information to identify, contact, or locate a single person, or to identify an individual in context.</em></p>
	<p>Please read our privacy policy carefully to get a clear understanding of how we collect, use, protect or otherwise handle your Personally Identifiable Information in accordance with our website.</p>
	<h4>What personal information do we collect from the people that visit our blog, website or app?</h4>
	<p>When using our site, as appropriate, you may be asked to enter your name, email address or other details to help you with your experience.</p>
	<h4>How do we use your information?</h4>
	<p>We may use the information we collect from you when you register, make a purchase, sign up for our newsletter, unlock the content, respond to a survey or marketing communication, surf the website, or use certain other site features in the following ways:</p>
	<ul>
	<li>To personalize user's experience and to allow us to deliver the type of content and product offerings in which you are most interested</li>
	<li>To improve our website in order to better serve you.</li>
	<li>To allow us to better service you in responding to your customer service requests.</li>
	<li>To administer a contest, promotion, survey or other site feature.</li>
	<li>To send periodic emails regarding your order or other products and services.</li>
	</ul>
	<h4>Do we use 'cookies'?</h4>
	<p>Yes. Cookies are small files that a site or its service provider transfers to your computer's hard drive through your Web browser (if you allow) that enables the site's or service provider's systems to recognize your browser and capture and remember certain information. For instance, we use cookies to help us remember and process the items in your shopping cart. They are also used to help us understand your preferences based on previous or current site activity, which enables us to provide you with improved services. We also use cookies to help us compile aggregate data about site traffic and site interaction so that we can offer better site experiences and tools in the future.</p>
	<p>We use cookies to:</p>
	<ul>
	<li>Understand and save user's preferences for future visits.</li>
	<li>Memorize the users who have unlocked access to premium content.</li>
	</ul>
	<p>If you disable cookies off, some features will be disabled. It will affect the users experience and some of our services will not function properly.</p>
	<h4>Third Party Disclosure</h4>
	<p>We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information unless we provide you with advance notice. This does not include website hosting partners and other parties who assist us in operating our website, conducting our business, or servicing you, so long as those parties agree to keep this information confidential. We may also release your information when we believe release is appropriate to comply with the law, enforce our site policies, or protect ours or others' rights, property, or safety.</p>
	<p>However, non-personally identifiable visitor information may be provided to other parties for marketing, advertising, or other uses.</p>
	<h4>Third party links</h4>
	<p>Occasionally, at our discretion, we may include or offer third party products or services on our website. These third party sites have separate and independent privacy policies. We therefore have no responsibility or liability for the content and activities of these linked sites. Nonetheless, we seek to protect the integrity of our site and welcome any feedback about these sites.</p>
	<p>If there are any questions regarding this privacy policy you may contact us.</p>"
		) );

	$cmb->add_field( array(
		'id' => 'term-content-close',
		'type' => 'dependency',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );

	$cmb->add_field( array(
		'id' => 'term-pages',
		'type' => 'dependency',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Terms of Use', 'content-locker' ),
			'id' => 'terms_of_use_page',
			'type' => 'select',
			'classes' => 'no-border',
			'options_cb' => 'cl_cmb_get_pages',
			'desc' => esc_html__( 'Select a page which contains the "Terms of Use" for the lockers or/and your website.', 'content-locker' )
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Privacy Policy', 'content-locker' ),
			'id' => 'privacy_policy_page',
			'type' => 'select',
			'options_cb' => 'cl_cmb_get_pages',
			'desc' => esc_html__( 'Select a page which contains the "Privacy Policy" for the lockers or/and your website.', 'content-locker' ),
		) );

	$cmb->add_field( array(
		'id' => 'term-pages-close',
		'type' => 'dependency',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );

$cmb->add_field( array(
	'id' => 'settings-terms-tab-close',
	'type' => 'section_end',
	'render_row_cb' => 'cl_cmb_tab_close_tag'
) );
