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

    <?php settings_errors('github_actions_messages'); ?>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-1">Theme Settings</a></li>
        <li class=""><a href="#tab-2">Trigger Workflow</a></li>
        <li class=""><a href="#tab-3">Installed Themes</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <form action="<?php echo admin_url(); ?>options.php" method="post">
                <?php
                // Output nonce, action, and option_page fields for a settings page
                settings_fields('github_actions_theme_group');

                // Output sections and fields for a settings page
                do_settings_sections('github-actions-theme');

                // Output Save Settings button
                submit_button('Save Theme Settings');
                ?>
            </form>
        </div>
        <div id="tab-2" class="tab-pane">
            <h3>Trigger Workflow</h3>
            <div>
                <h4>Trigger workflow for the theme below</h4>
                <?php
                    // Retrieve theme data from options
                    $options = get_option('themes_github_options');

                    $repository_owner = isset($options['ga_username']) ? esc_attr($options['ga_username']) : '';
                    $github_repository_name = isset($options['ga_theme_repository_name']) ? esc_attr($options['ga_theme_repository_name']) : '';
                    $repository_reference = isset($options['ga_theme_repository_branch']) ? esc_attr($options['ga_theme_repository_branch']) : 'main';
            
                ?>
                <p><strong>Repository Owner:</strong> <?php echo $repository_owner; ?></p>
                <p><strong>Repository Name:</strong> <?php echo $github_repository_name; ?></p>
                <p><strong>Repository Branch:</strong> <?php echo $repository_reference; ?></p>
                <p id="response-success"></p>
                <p id="response-error"></p>
                <div class="lds-spinner" id="loading-spinner">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <button id="trigger-workflow-button" class="button button-primary">Trigger Workflow</button>
            </div>
        </div>
        <div id="tab-3" class="tab-pane">
            <h3>View Installed Themes</h3>
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