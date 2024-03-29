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
            
            check_ajax_referer('github_actions_theme_nonce', 'nonce');

            if (!current_user_can('manage_options')) {

                wp_die('Unauthorized');

            }

            // Retrieve GitHub options

            $options = get_option('themes_github_options');

            $token = get_option('github_options_defaults');


            $github_access_token = isset($token['github_access_token']) ? esc_attr($token['github_access_token']) : '';

            $repository_owner = isset($options['ga_username']) ? esc_attr($options['ga_username']) : '';

            $github_repository_name = isset($options['ga_theme_repository_name']) ? esc_attr($options['ga_theme_repository_name']) : '';

            $repository_reference = isset($options['ga_theme_repository_branch']) ? esc_attr($options['ga_theme_repository_branch']) : 'main';

            // Check if the repository is private

            $is_private = isset($options['ga_private_theme']) && $options['ga_private_theme'] === '1';

            // GitHub API URL for repository information

            $api_url = 'https://api.github.com/repos/' . urlencode($repository_owner) . '/' . urlencode($github_repository_name);
            
            $headers = array(

                'Authorization: Bearer ' . $github_access_token,

                'User-Agent: Github Actions Trigger',

                'Accept: application/vnd.github.v3+json',

                'X-GitHub-Api-Version: 2022-11-28'

            );
        
            // Initialize cURL session
            $curl = curl_init();

            // Set cURL options
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL session
            $response_body = curl_exec($curl);
            $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            // Close cURL session
            curl_close($curl);

            // Debug: Print cURL request and response details
            self::debugCurlDetails($api_url, $headers, $response_code, $response_body);

            if ($response_code === 200) {

                $repository_data = json_decode($response_body, true);

                // Get the clone URL for the repository
                $clone_url = $is_private
                ? $repository_data['clone_url']
                : "https://username:{$github_access_token}@github.com/{$repository_owner}/{$github_repository_name}.git";
    
                $clone_url = "https://username:{$github_access_token}@github.com/{$repository_owner}/{$github_repository_name}";

                echo 'CLONE URL:' . $clone_url . PHP_EOL; 

                // Construct the Git clone command

                $git_clone_command = "git clone {$clone_url} --branch {$repository_reference} --single-branch";

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

                    // Set up GitHub webhook

                    self::setupWebhook($github_access_token, $repository_owner, $github_repository_name, $repository_reference);

                } else {

                    // Handle git clone error

                    echo 'Failed to clone the repository. Check your GitHub access token and repository information.';

                    self::debugGitCloneDetails($output, $return_code);

                }

            } else {

                // Handle GitHub API request error

                echo 'Failed to retrieve repository information from GitHub.';

            }

            wp_die();

        }

        private static function setupWebhook($github_access_token, $repository_owner, $github_repository_name, $repository_reference) {

            // Webhook endpoint path

            $webhook_path = '/wp-json/github-actions/v1/webhook';

            // Construct the full webhook URL dynamically using home_url

            $webhook_url = home_url($webhook_path);

            // GitHub API URL for listing repository hooks

            $hooks_url = "https://api.github.com/repos/{$repository_owner}/{$github_repository_name}/hooks";

            $headers = array(

                'Authorization' => 'token ' . $github_access_token,

                'User-Agent'    => 'GitHub Actions Trigger',

            );

            // Fetch existing hooks

            $existing_hooks = wp_remote_get($hooks_url, array('headers' => $headers));

        
            if (is_array($existing_hooks) && !is_wp_error($existing_hooks)) {

                $existing_hooks = json_decode($existing_hooks['body'], true);

                // Check if a webhook for our URL already exists

                $webhook_id = self::findWebhookId($existing_hooks, $webhook_url);

                if ($webhook_id) {

                    // If found, update the existing webhook

                    $url = "https://api.github.com/repos/{$repository_owner}/{$github_repository_name}/hooks/{$webhook_id}";

                    $method = 'PATCH'; // Use PATCH to update existing hook

                    $data = json_encode(array(

                        'config' => array(

                            'url'          => $webhook_url,

                            'content_type' => 'form',

                            'insecure_ssl' => '0',

                            'secret'       => 'GITHUB_ACTIONS_SECRET',

                            'branches'     => [$repository_reference]

                        )

                    ));

                } else {

                    // If not found, create a new webhook

                    $url = "https://api.github.com/repos/{$repository_owner}/{$github_repository_name}/hooks";

                    $method = 'POST'; // Use POST to create a new hook

                    $data = json_encode(array(

                        'name'   => 'web',

                        'active' => true,

                        'events' => ['push'],

                        'config' => array(

                            'url'          => $webhook_url,

                            'content_type' => 'form',

                            'insecure_ssl' => '0',

                            'secret'       => 'GITHUB_ACTIONS_SECRET',

                            'branches'     => [$repository_reference]

                        )

                    ));

                }

                // Send request to GitHub API

                $response = wp_remote_request($url, array(

                    'method'    => $method,

                    'headers'   => $headers,

                    'body'      => $data,

                    'sslverify' => true,

                ));

                if (is_array($response) && isset($response['response']['code']) && ($response['response']['code'] === 200 || $response['response']['code'] === 201)) {

                    echo 'Webhook set up successfully.';

                } else {

                    echo 'Failed to set up or update webhook.';

                }

            } else {

                echo 'Failed to fetch existing hooks.';

            }

        }

        // Helper function to find webhook ID by URL

        private static function findWebhookId($hooks, $target_url) {

            foreach ($hooks as $hook) {

                if (isset($hook['config']['url']) && $hook['config']['url'] === $target_url) {

                    return $hook['id'];

                }

            }

            return false;

        }

        private static function debugCurlDetails($api_url, $headers, $response_code, $response_body) {
            // Debug: Print cURL request and response details
            echo '<pre>';
            echo 'cURL Request URL: ' . esc_html($api_url) . PHP_EOL;
            echo 'cURL Request Headers: ' . esc_html(json_encode($headers, JSON_PRETTY_PRINT)) . PHP_EOL;
            echo 'cURL Response Code: ' . esc_html($response_code) . PHP_EOL;
            echo 'cURL Response Body: ' . esc_html($response_body) . PHP_EOL;
            echo '</pre>';
        }

        private static function debugGitCloneDetails($output, $return_code) {
            // Debug: Log git clone command output and return code
            echo 'Git Clone Command Output: ' . print_r($output, true);
            echo 'Git Clone Return Code: ' . $return_code;
        }

    }
}