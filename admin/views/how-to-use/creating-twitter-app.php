<h2>Creating Twitter App</h2>

<p>
    1. Head over to apps.twitter.com and login if you aren't logged in already.
</p>
<p>
    2. You will see a new button saying "Create New App". Click that button.
</p>
<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/21.jpg" />
</p>
<p>
    3. On the next screen, you will be asked for a few details. Enter those details as required.
</p>
<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/22.jpg" />
</p>
<p>
    When asked for a Callback URL, scroll down on this page and copy the URL from below section as seen the screenshot:
</p>
<table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th>Field</th>
            <th>How To Fill</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="mts-cl-title">Name</td>
            <td>The best app name is your website name.</td>
        </tr>
        <tr>
            <td class="mts-cl-title">Description</td>
            <td>
                <p>Explain why you ask for the credentials, e.g:</p>
                <p><i>This application asks your credentials in order to unlock the content. Please read the TOS.</i></p>
            </td>
        </tr>
        <tr>
            <td class="mts-cl-title">Website</td>
            <td>
                <p>Paste your website URL:</p>
                <p>
                    <?php echo get_bloginfo( 'url' ); ?>
                </p>
            </td>
        </tr>
        <tr>
            <td class="mts-cl-title">Callback URL</td>
            <td>
                <p>Paste the URL:</p>
                <p><i><?php echo get_bloginfo( 'url' ); ?>/wp-admin/admin-ajax.php?action=mts_cl_connect&amp;handler=twitter</i></p>
            </td>
        </tr>
    </tbody>
</table>
<br />
<p>
    4. Next, click the Settings tab and scroll down to check the option that says "Allow this application to be used to 'sign in with Twitter'".
</p>
<p>
    5. Head over the Permissions tab and ensure "Read and Write" as well as the "Request email addresses from users" option is selected.
</p>
<p>
    6. Go to "Keys and Access Tokens" tab and grab the "API Key" as well as the "API Secret". You will need them in the next step.
</p>
<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/24.jpg" />
</p>
<p>
    7. Go back to the <a href="<?php echo cl_get_admin_url('settings#twitter_consumer_key') ?>">Content Locker settings page</a> and paste the codes as seen in the screenshot below:
</p>
<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/25.jpg" />
</p>
