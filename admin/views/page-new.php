<div class="wrap mts-wrap">
    <div class="mts-items">
    	<h1 class="mt30 mb40"><?php esc_html_e( 'Create New Locker', 'content-locker' ) ?></h1>

	    <div class="postbox cl-box">

	        <h4 class="cl-box-header"><?php esc_html_e( 'Social Locker', 'content-locker' ) ?></h4>
	        <div class="cl-box-body">
	            <p><?php esc_html_e( 'Asks users to "unlock content with a like or share".', 'content-locker' ) ?></p>
	            <p><?php esc_html_e( 'Perfect way to get more likes and shares, attract social traffic and improve some social metrics.', 'content-locker' ) ?></p>
	        </div>
	        <div class="cl-box-footer">

	            <a href="<?php echo admin_url( 'post-new.php?post_type=content-locker&cl_item_type=social-locker' ); ?>" class="button button-large">
	                <i class="fa fa-plus-circle"></i><span><?php esc_html_e( 'Create Locker', 'content-locker' ) ?></span>
	            </a>

	        </div>
	    </div>

		<div class="postbox cl-box">

	        <h4 class="cl-box-header"><?php esc_html_e( 'Sign-In Locker', 'content-locker' ) ?></h4>
	        <div class="cl-box-body">
	            <p><?php esc_html_e( 'Asks users to "sign-in through social networks or subscription" form to unlock the content.', 'content-locker' ) ?></p>
	            <p><?php esc_html_e( 'Use these lockers to collect subscribers.', 'content-locker' ) ?></p>
	        </div>
	        <div class="cl-box-footer">

	            <a href="<?php echo admin_url( 'post-new.php?post_type=content-locker&cl_item_type=signin-locker' ); ?>" class="button button-large">
	                <i class="fa fa-plus-circle"></i><span><?php esc_html_e( 'Create Locker', 'content-locker' ) ?></span>
	            </a>

	        </div>
	    </div>

    </div>
</div>
