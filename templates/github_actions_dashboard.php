<?php
// Check if the user has submitted the settings
// WordPress adds the "settings-updated" $_GET parameter to the URL

$is_update = isset($_GET['settings-updated']);

// Determine the message based on the update status
$message = $is_update ? __('Settings Updated', 'github-actions') : __('Settings Saved', 'github-actions');

if ($is_update) {
    // Add a settings updated message with the class of "updated"
    add_settings_error('github_actions_messages', 'ga_message', $message, 'updated');
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <?php if ( ! get_option('hide-ga-welcome', false)) { ?>
    <div class="ga-welcome-panel welcome-panel">
        <a href="<?php echo admin_url('admin.php?page=github-actions-trigger&github-actions-trigger-welcome=0')?>"
            class="ga-welcome-panel-close welcome-panel-close" aria-label="Dismiss the welcome panel">Dismiss</a>
        <div class="ga-welcome-panel-content">
            <h3><?php _e( "Thanks for installing Github Actions!" ); ?></h3>

            <p class="about-description">Here's how to get started:</p>
            <div class="ga-welcome-panel-column-container">
                <div class="ga-welcome-panel-column">
                    <h4>Actions</h4>
                    <ul>
                        <li class="welcome-icon welcome-edit-page">
                            <?php
                        $github_credentials_url = esc_url(add_query_arg('page', 'github-actions-trigger', admin_url('admin.php')));
                        printf(
                            __('Add your <a href="%s">GitHub</a> credentials', 'github-actions'),
                            $github_credentials_url
                        );
                        ?>
                        </li>

                        <li>
                            <?php
                        $install_theme_url = esc_url(add_query_arg('page', 'github-actions-themes', admin_url('admin.php')));
                        printf('<a href="%s" class="welcome-icon welcome-add-page">%s</a>', $install_theme_url, esc_html__('Install a Theme', 'github-actions'));
                        ?>
                        </li>

                        <li>
                            <?php
                        $install_plugin_url = esc_url(add_query_arg('page', 'github-actions-plugins', admin_url('admin.php')));
                        printf('<a href="%s" class="welcome-icon welcome-add-page">%s</a>', $install_plugin_url, esc_html__('Install a Plugin', 'github-actions'));
                        ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php settings_errors('github_actions_messages'); ?>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-1">Manage Settings</a></li>
        <li class=""><a href="#tab-2">Updates</a></li>
        <li class=""><a href="#tab-3">About</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <form action="<?php echo admin_url(); ?>options.php" method="post">
                <?php
                // Output nonce, action, and option_page fields for a settings page
                settings_fields('github_actions_admin_group');

                // Output sections and fields for a settings page
                do_settings_sections('github-actions-trigger');

                // Output Save Settings button
                submit_button('Save Github Token');
                ?>
            </form>
        </div>
        <div id="tab-2" class="tab-pane">
            <h3>Updates</h3>
            <p>Upcoming Updates</p>
        </div>
        <div id="tab-3" class="tab-pane">
            <h3>About</h3>
            <p><b>About the plugin</b></p>

            <p>
                The GitHub token is required for accessing private repositories. If your GitHub repository is public,
                you do not need to provide a token. However, for private repositories, a GitHub access token with the
                'repo'
                scope is necessary for authentication.
            </p>

            <p>
                To set up webhooks (for automatic updating of your theme/plugin) and clone private repositories, ensure
                your GitHub token has the required
                permissions.
                You can manage these permissions in your GitHub repository settings.
                <a href="https://docs.github.com/en/developers/apps/building-oauth-apps/scopes-for-oauth-apps#available-scopes"
                    target="_blank" rel="noopener noreferrer">Learn more about GitHub scopes and permissions</a>.
            </p>
        </div>

    </div>



    <div class="gat-footer">
        <hr>
        <div>
            <p>Copyright &copy; <?php echo date('Y'); ?> <a class="" target="_blank"
                    href="https://wilsondevops.com">Wilson</a></p>
        </div>
    </div>

</div>