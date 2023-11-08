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
            add_action('wp_ajax_save_settings', array(__CLASs__, 'saveSettings'));
            add_action('wp_ajax_trigger_workflow', array(__CLASS__, 'triggerWorkflow'));
        }

        public static function trigger_database_action(){
            $github_username = get_option('github_username', '');
            $github_repository = get_option('github_repository', '');
            $github_access_token = get_option('github_access_token', '');
            $event_type = get_option('github_event_type', ''); // Retrieve the event type from the database
        }

        public static function enqueue_admin_scripts(){
            wp_enqueue_script('github-actions-script', GITHUB_ACTIONS_PLUGIN_URL . 'assets/js/github-actions.js');
            wp_enqueue_style('github-actions-style', GITHUB_ACTIONS_PLUGIN_URL . 'assets/css/github-actions.css');
            wp_enqueue_style('bootstrap-style', GITHUB_ACTIONS_PLUGIN_URL . 'assets/css/bootstrap.min.css');
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
            $github_repository = sanitize_text_field($_POST['github_repository']);
            $github_access_token = sanitize_text_field($_POST['github_access_token']);
            $github_event_type = sanitize_text_field($_POST['github_event_type']); // Get event type from the form
    
            update_option('github_username', $github_username);
            update_option('github_repository', $github_repository);
            update_option('github_access_token', $github_access_token);
            update_option('github_event_type', $github_event_type); // Save the event type to the database
    
            echo 'Settings saved successfully.';
    
            wp_die();
        }

        public static function triggerWorkflow() {
            if (!current_user_can('manage_options')) {
                wp_die('Unauthorized');
            }
    
            $github_username = sanitize_text_field($_POST['github_username']);
            $github_repository = sanitize_text_field($_POST['github_repository']);
            $github_access_token = sanitize_text_field($_POST['github_access_token']);
            $github_event_type = sanitize_text_field($_POST['github_event_type']); // Get event type from the form
    
            // Trigger the GitHub Actions workflow using $github_username, $github_repository, and $github_access_token
            $repository_owner = $github_username;
            $repository_name = $github_repository;
            $event_type = $github_event_type; // Use the event type from the form
    
            $url = "https://api.github.com/repos/{$repository_owner}/{$repository_name}/dispatches";
            $headers = array(
                'Authorization: token ' . $github_access_token,
                'Content-Type: application/json',
                'User-Agent: GitHub Actions Trigger'
            );
    
            $data = json_encode(array('event_type' => $event_type));
    
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
            $response = curl_exec($curl);
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
            curl_close($curl);
            var_dump($response);
    
            if ($http_status === 204) {
                echo 'Workflow triggered successfully.';
            } else {
                echo 'Failed to trigger workflow.';
            }
    
            wp_die();
        }
    }
    
}