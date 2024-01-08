<?php
/**
 * @package GithubActions
*/

namespace GAP\Theme;

if( ! class_exists('Github_Actions_Trigger_Workflow')){

    class Github_Actions_Trigger_Workflow{
        public static function register(){
            // Register AJAX actions
            add_action('wp_ajax_trigger_workflow_action', array(__CLASS__, 'triggerWorkflow')); 
        }   
        
        public static function triggerWorkflow() {
            if (!current_user_can('manage_options')) {
                wp_die('Unauthorized');
            }
            
            check_ajax_referer('github_actions_theme_nonce', 'nonce');
        
            // Retrieve GitHub options
            $options = get_option('themes_github_options');
            $token = get_option('github_options_defaults');
            
            // You can return a response if needed
            $github_access_token = isset($token['github_access_token']) ? esc_attr($token['github_access_token']) : '';
            $repository_owner = isset($options['ga_username']) ? esc_attr($options['ga_username']) : '';
            $github_repository_name = isset($options['ga_theme_repository_name']) ? esc_attr($options['ga_theme_repository_name']) : '';
            $repository_reference = isset($options['ga_theme_repository_branch']) ? esc_attr($options['ga_theme_repository_branch']) : 'main';
        
            // GitHub API URL for repository information
            $api_url = "https://api.github.com/repos/{$repository_owner}/{$github_repository_name}";
            $headers = array(
                'Authorization: token ' . $github_access_token,
                'User-Agent: Github Actions Trigger',
                'Accept: application/vnd.github.v3+json',
            );
        
            // Fetch repository information
            $response = wp_remote_get($api_url, ['headers' => $headers]);
        
            if (is_array($response) && !is_wp_error($response)) {
                $repository_data = json_decode($response['body'], true);
        
                // Get the clone URL for the repository
                $clone_url = $repository_data['clone_url'];
        
                // Construct the Git clone command
                $git_clone_command = "git clone {$clone_url} --branch {$repository_reference} --single-branch";
        
                // Execute the Git clone command
                exec($git_clone_command, $output, $return_code);
        
                if ($return_code === 0) {
                    // Move the downloaded theme directory to the WordPress themes directory
                    $theme_directory = get_theme_root() . '/' . $github_repository_name;
                    rename($github_repository_name, $theme_directory);
        
                    // Activate the theme
                    switch_theme($github_repository_name);
        
                    // Optionally install and activate theme dependencies (e.g., npm install)
        
                    // Additional steps as required
                    // ...
        
                    echo 'Theme installed and activated successfully.';
                } else {
                    // Handle git clone error
                    echo 'Failed to clone the repository. Check your GitHub access token and repository information.';
                }
            } else {
                // Handle GitHub API request error
                echo 'Failed to retrieve repository information from GitHub.';
            }
        
            wp_die();
        }
        

    }

}