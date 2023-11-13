<?php
/**
 * @package GithubActions
*/

if( ! class_exists('Github_Actions_Webhook')){

    class Github_Actions_Webhook{
        public static function register_services(){
            add_action('rest_api_init', array(__CLASS__, 'register_routes'));
        }

        public static function register_routes(){
            register_rest_route('github-actions/v1', '/webhook', array(
                'methods' => 'POST',
                'callback' => array(__CLASS__, 'webhook_callback')
            ));
        }

        public static function webhook_callback(){
            // Handle GitHub webhook payload here
            $payload = json_decode(file_get_contents('php://input'), true);

            // Perform actions based on the payload (e.g., update the repository)

            // Respond to GitHub to acknowledge the webhook
            status_header(200);
            echo 'Webhook received successfully.';
        }
    }

}