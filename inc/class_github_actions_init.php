<?php
/**
 * @package GithubActions
*/
if( ! class_exists('Github_Actions_Init')){

    class Github_Actions_Init{
        
        private static $access_token;
        
        public static function register_services(){
            // Load saved credentials
            self::$access_token = get_option('github_access_token', '');
            self::trigger_database_action();
        
            add_action( 'admin_enqueue_scripts', array(__CLASS__, 'enqueue_admin_scripts') );
            add_action( 'admin_menu', array(__CLASS__, 'admin_pages') );
            add_filter( 'plugin_action_links_'.GITHUB_ACTIONS_PLUGIN_NAME, array(__CLASS__, 'settings_link') );

            // Register AJAX actions
            add_action('wp_ajax_save_settings', array(__CLASS__, 'saveSettings'));
            add_action('wp_ajax_trigger_workflow', array(__CLASS__, 'triggerWorkflow'));
        }

        public static function trigger_database_action(){
            $github_username = get_option('github_username', '');
            $github_access_token = get_option('github_access_token', '');
            $repository_name = get_option('repository_name', '');
            $repository_branch = get_option('repository_branch', ''); 
        }

        public static function enqueue_admin_scripts(){
            wp_enqueue_script('github-actions-script', GITHUB_ACTIONS_PLUGIN_URL . 'assets/js/github-actions.js');
            wp_enqueue_style('github-actions-style', GITHUB_ACTIONS_PLUGIN_URL . 'assets/css/github-actions.css');
            // wp_enqueue_style('bootstrap-style', GITHUB_ACTIONS_PLUGIN_URL . 'assets/css/bootstrap.min.css');
        }

        public static function admin_pages(){
            add_menu_page('Github Actions Trigger', 'Github Actions', 'manage_options', 'github-actions-trigger', array(__CLASS__, 'github_actions_page'), 'dashicons-admin-site-alt', 110);
        }

        public static function github_actions_page(){
            if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_page.php')){
                require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_page.php');
            }
        }

        public static function settings_link($links){
            $settings_link = '<a href="admin.php?page=github-actions-trigger">Settings</a>';
            array_push( $links, $settings_link);
            return $links;
        }

        public static function saveSettings() {
            if (!current_user_can('manage_options')) {
                wp_die('Unauthorized');
            }
    
            $github_username = sanitize_text_field($_POST['github_username']);
            $github_access_token = sanitize_text_field($_POST['github_access_token']);
            $repository_name = sanitize_text_field($_POST['repository_name']);
            $repository_branch = sanitize_text_field($_POST['repository_branch']); // Get event type from the form
    
            update_option('github_username', $github_username);
            update_option('github_access_token', $github_access_token);
            update_option('repository_name', $repository_name);
            update_option('repository_branch', $repository_branch); 
    
            echo 'Settings saved successfully.';
    
            wp_die();
        }

        public static function triggerWorkflow() {
            if (!current_user_can('manage_options')) {
                wp_die('Unauthorized');
            }
    
            $github_username = sanitize_text_field($_POST['github_username']);
            $github_access_token = sanitize_text_field($_POST['github_access_token']);
            $repository_name = sanitize_text_field($_POST['repository_name']);
            $repository_branch = sanitize_text_field($_POST['repository_branch']); 
    
            // Trigger the GitHub Actions Workflow using $github_username, $github_access_token, $repository_name, and  $repository_name
            $repository_owner = $github_username;
            $github_repository_name = $repository_name;
            $repository_reference = $repository_branch;
    
            $url = "https://api.github.com/repos/{$repository_owner}/{$github_repository_name}/zipball/{$repository_reference}";
            $headers = array(
                'Authorization: Bearer ' . $github_access_token,
                'User-Agent: Github Actions Trigger',
                'X-GitHub-Api-Version: 2022-11-28',
                "Accept: application/vnd.github+json"
            );

            $curl = curl_init($url);
            
            // Set cURL options
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    
            // Execute the cURL session and store the response
            $response = curl_exec($curl);

            // Check for cURL errors
            if (curl_errno($curl)) {
                echo 'cURL error: ' . curl_error($ch);
                exit;
            }

            // Check the HTTP status code of the response
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($http_status !== 200) {
                echo 'GitHub API returned a non-200 status code: ' . $http_status;
                exit;
            }

            // Close the cURL session
            curl_close($curl);

            // Define the directory where you want to save the ZIP archive
            // Get the WordPress uploads directory information
            $upload_dir = wp_upload_dir();

            // Define the path to save the ZIP archive in the uploads directory
            $zip_file = $upload_dir['path'] . '/theme-archive.zip';

            // Make sure the directory exists, create it if necessary
            if (!file_exists($upload_dir['path'])) {
                mkdir($upload_dir['path'], 0755, true);
            }

            // Now you can save the ZIP archive to the uploads directory
            file_put_contents($zip_file, $response);

            // Unzip the repository to the WordPress themes directory
            $theme_directory = get_theme_root();
            $zip = new ZipArchive;
            if ($zip->open($zip_file) === TRUE) {
                // Get the first entry in the ZIP file (assuming it's the repository name)
                $first_entry = $zip->getNameIndex(0);

                // Define the destination folder with the repository name
                $destination_folder = $theme_directory . '/' . $first_entry;

                // Extract the contents to the destination folder
                if ($zip->extractTo($theme_directory) && $zip->close()) {
                    echo 'ZIP archive has been extracted successfully.';

                    // Activate the theme in WordPress
                    switch_theme($first_entry);

                    // Optional: Delete the ZIP file if you no longer need it
                    unlink($zip_file);
                }else {
                    echo 'Failed to extract the ZIP archive or close the ZipArchive.';
                }
            } else {
                echo 'Failed to open the ZIP archive.';
            }

            // Activate the theme in WordPress
            
            $new_theme = get_theme_root() . '/' . $theme_name; // Replace with the actual folder name of the theme
            if (file_exists($new_theme)) {
                switch_theme($new_theme);
            }

            // Optional: Delete the ZIP file if you no longer need it
            unlink($zip_file);
    
            wp_die();
        }
    }
    
}