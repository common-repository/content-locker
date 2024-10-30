<div class="mts-cl-subscription-message">
	<?php esc_html_e( 'Cannot sign in via social networks? Enter your email manually.', 'content-locker' ); ?>
</div>

<form class="mts-cl-subscription-form">

    <div class="mts-cl-subscription-form-inner-wrap">

        <div class="mts-cl-field mts-cl-field-email">
            <div class="mts-cl-field-control">
                <input type="email" class="mts-cl-input mts-cl-input-required" id="mts-cl-input-email" placeholder="<?php esc_html_e( 'Please enter your email address.', 'content-locker' ) ?>" autocomplete="off" name="email">
            </div>
        </div>

        <div class="mts-cl-field mts-cl-field-submit">
            <button class="mts-cl-button mts-cl-form-button mts-cl-submit"><?php esc_html_e( 'sign in to unlock', 'content-locker' ) ?></button>
            <div class="mts-cl-note mts-cl-nospa"><?php esc_html_e( 'Your email address is 100% safe from spam!', 'content-locker' ) ?></div>
        </div>

    </div>

</form>
