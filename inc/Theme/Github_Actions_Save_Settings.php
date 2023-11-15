<?php
/**
 * @package GithubActions
*/

namespace GAP\Theme;

if( ! class_exists('Github_Actions_Save_Settings')){

    class Github_Actions_Save_Settings{
        public static function register(){
            // Register AJAX actions
            add_action('wp_ajax_save_settings', array(__CLASS__, 'saveSettings')); 
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
    }

}