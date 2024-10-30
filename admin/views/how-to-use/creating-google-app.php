<h2>Getting Google Client ID</h2>

<p>
    1. Head over to <a href="https://console.developers.google.com/project">console.developers.google.com</a>
</p>
<p>
    2. You will be greeted with a blank screen where you can create a new project.
</p>
<p>
    3. Click on the "Create Project" button and give your Project a name.
</p>

<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/26.jpg" />
</p>

<p>
    4. Once you do that, head over to the Library and search for "Google+".
</p>

<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/27.jpg" />
</p>
<p>

    5. Click on the "Google+ API" link and hit the Enable button.
</p>

<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/28.jpg" />
</p>
<p>

    6. Do the same for "YouTube Data API v3".
</p>
<p>
    7. Then, click on the "Credentials" button on the left and hit "Create Credentials". Then, choose "OAuth client ID."
</p>

<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/29.jpg" />
</p>
<p>

    8. Enter the details on the next screen. When asked for 'Authorised Redirect URIs', paste the URL you see below on this page.
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
            <td class="mts-cl-title">Application Type</td>
            <td>
                <p>Web Application</p>
            </td>
        </tr>
        <tr>
            <td class="mts-cl-title">Authorized Javascript origins</td>
            <td>
                <p>Add the origins:</p>
                <p><i>http://yourdomain.com</i></p>
                <p><i>http://www.yourdomain.com</i></p>

            </td>
        </tr>
        <tr>
            <td class="mts-cl-title">Authorized redirect URIs</td>
            <td>
                <p>Paste the URL:</p>
                <p><i><?php echo get_bloginfo( 'url' ); ?>/wp-admin/admin-ajax.php?action=mts_cl_connect&amp;handler=google</i></p>
            </td>
        </tr>
    </tbody>
</table>
<br />
<p>
    9. On the next screen, you will see the Client ID. Copy it.
</p>
<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/30.jpg" />
</p>
<p>

    10. Paste it in the <a href="<?php echo cl_get_admin_url('settings#google_client_id') ?>">Content Locker settings</a> section designated for "Google Client ID".
</p>

<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/31.jpg" />
</p>
