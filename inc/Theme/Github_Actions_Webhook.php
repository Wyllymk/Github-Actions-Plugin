<?php

/**

 * @package GithubActions

*/

namespace GAP\Theme;

if( ! class_exists('Github_Actions_Webhook')){



    class Github_Actions_Webhook{

        public static function register(){

            add_action('rest_api_init', array(__CLASS__, 'register_routes'));

        }



        public static function register_routes(){

            register_rest_route('github-actions/v1', '/webhook', array(

                'methods' => 'POST',

                'callback' => array(__CLASS__, 'webhook_callback')

            ));

        }

        public static function webhook_callback() {
            // Get the GitHub signature from headers
            $github_signature = $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';
    
            // Get the raw request payload
            $payload = file_get_contents('php://input');
    
            // Secret known to GitHub and your server
            $webhook_secret = 'GITHUB_ACTIONS_SECRET'; // Replace with your secret
    
            // Verify GitHub signature
            if (self::verify_github_signature($github_signature, $payload, $webhook_secret)) {
                // Valid GitHub webhook request, perform actions
                self::sync_files();
    
                // Respond to GitHub to acknowledge the webhook
                status_header(200);
                echo 'Webhook received and processed successfully.';
            } else {
                // Invalid GitHub webhook request
                status_header(403);
                echo 'Invalid GitHub webhook request.';
            }
        }
    
        private static function verify_github_signature($github_signature, $payload, $webhook_secret) {
            list($algo, $hash) = explode('=', $github_signature, 2) + [null, null];
            $expected_hash = hash_hmac($algo, $payload, $webhook_secret);
    
            return hash_equals($hash, $expected_hash);
        }
    
        private static function sync_files() {

            $options = get_option('themes_github_options');

            $github_repository_name = isset($options['ga_theme_repository_name']) ? esc_attr($options['ga_theme_repository_name']) : '';
            $repository_reference = isset($options['ga_theme_repository_branch']) ? esc_attr($options['ga_theme_repository_branch']) : 'main';

            // Adjust these paths based on your WordPress setup
            $theme_directory = get_theme_root() . '/' . $github_repository_name;
            
            $git_command = "git pull origin {$repository_reference} --force 2>&1";

    
            // Move to the theme directory
            chdir($theme_directory);
    
            // Execute the Git pull command
            exec($git_command . ' 2>&1', $output, $return_code);

            / echo 'Git pull command: ' . $git_command . '<br>';
            // echo 'Git pull output: ' . implode('<br>', $output) . '<br>';
            // echo 'Git pull return code: ' . $return_code . '<br>';
    
            // Optionally, perform additional actions or error handling
            if ($return_code === 0) {
                // Git pull successful
                // Add additional steps if needed (e.g., npm install)
                echo 'Theme synced successfully.';
            } else {
                // Git pull failed
                echo 'Failed to sync the theme. Check logs for details.';
            }
        }

    }



}
