<div class="more-hint cmb-row">
	<div class="alert alert-hint mt0">
	    <p class="alert-title"><?php esc_html_e( 'How to use:', 'content-locker' ) ?></p>
	    <p>
			<?php echo wp_kses_post( __( 'Using above option automatically subscribe all the signed-up users to your mailing list. Free version only supports MailChimp & MailerLite.', 'content-locker' ) ) ?>
		</p>
	    <p style="padding-top: 3px;">
			<?php echo wp_kses_post( sprintf( __( '<a href="%s" class="button" target="_blank">See emails</a> of users who already signed-up or <a href="%s" class="button" target="_blank">export emails</a> in the CSV format.', 'content-locker' ),  admin_url( 'edit.php?post_type=cl-lead'), admin_url( 'edit.php?post_type=content-locker&page=cl-export') ) ) ?>
		</p>
	</div>
</div>
