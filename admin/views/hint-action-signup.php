<?php

$default_role = get_option('default_role');
?>
<div class="more-hint cmb-row">
    <div class="alert alert-hint mt0">
        <p style="padding-top: 3px;">
			<?php echo wp_kses_post( __( '<strong>Login Mode (<underline>Manual</underline>): </strong> The account will be created but the user will not be logged in. The user will have to log in manually by using the login details sent via email. The locked content will be available instantly after clicking on the Sign-In buttons.', 'content-locker' ) ) ?>
        </p>
        <p>
			<?php printf( wp_kses_post( __( '<strong>New User Role:</strong> All new users will be assigned to the role %s (<a href="%s" target="_blank">change</a>).', 'content-locker' ) ), $default_role, admin_url('options-general.php') ) ?>
        </p>
        <p>
			<?php echo wp_kses_post( __( '<strong>Welcome E-mail:</strong> By default new users receive the standard wordpress welcome message. You can change it if you want. To customize the welcome mail you can use this free plugin <a href="https://wordpress.org/plugins/search.php?q=Welcome+Email" target="_blank">Click here</a>.', 'content-locker' ) ) ?>
        </p>
    </div>
</div>
