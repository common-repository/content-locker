<h2>Getting LinkedIn Client ID</h2>

<p>
    If you want to enable users to use their LinkedIn accounts to unlock the content, you need to get an API key from LinkedIn to enable that functionality.
</p>
<p>
    1. Go to <a href="https://www.linkedin.com/secure/developer">linkedin.com/secure/developer</a> and click on "Create Application."
</p>
<p>
    2. On the next screen, add your details like shown in the screenshot below:
</p>
<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/32.jpg" />
</p>
<p>
    3. Next, you will be shown the Client ID and some options. Set them up like the screenshot below:
</p>
<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/33.jpg" />
</p>
<p>
    For the "Accept" URL, the one shown on this page below.
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
            <td class="mts-cl-title">Company</td>
            <td>Select an existing company or create your own one (you can use your website name as a company name).</td>
        </tr>
        <tr>
            <td class="mts-cl-title">Name</td>
            <td>The best name is your website name.</td>
        </tr>
        <tr>
            <td class="mts-cl-title">Description</td>
            <td>
                <p>Explain what your app does, e.g:</p>
                <p><i>This application asks your credentials in order to unlock the content. Please read the Terms of Use to know how these credentials will be used.</i></p>
            </td>
        </tr>
        <tr>
            <td class="mts-cl-title">Application Logo URL</td>
            <td>
                <p>Paste an URL to your logo (80x80px). Or use this default logo:</p>
                <p><i><?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/default-logo.png</i></p>
            </td>
        </tr>
        <tr>
            <td class="mts-cl-title">Application Use</td>
            <td>
                <p>Select "Other" from the list.</p>
            </td>
        </tr>
        <tr>
            <td class="mts-cl-title">Website URL</td>
            <td>
                <p>Paste your website URL:</p>
                <p><i><?php echo get_bloginfo( 'url' ); ?></i></p>
            </td>
        </tr>
        <tr>
            <td class="mts-cl-title">Business Email</td>
            <td>
                <p>Enter your email to receive updates regarding your app.</p>
            </td>
        </tr>
        <tr>
            <td class="mts-cl-title">Business Phone</td>
            <td>
                <p>Enter your phone. It will not be visible for visitors.</p>
            </td>
        </tr>
    </tbody>
</table>
<br />
<p>
    4. After setting the Application to "Live" status, copy the client ID and client secret you saw in step 3 and paste them in the <a href="<?php echo cl_get_admin_url('settings#linkedin_client_id') ?>">Content Locker settings page</a>    like below.
</p>
<p class="mts-cl-img">
    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/34.jpg" />
</p>
