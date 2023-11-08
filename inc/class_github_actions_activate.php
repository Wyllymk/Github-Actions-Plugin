<?php
/**
 * @package GithubActions
 */
if( ! class_exists('Github_Actions_Activate')){

    class Github_Actions_Activate{
        public static function activate(){
            flush_rewrite_rules();
            self::github_actions_trigger_activate(); // Call the initialization method
        }

        public static function github_actions_trigger_activate() {
            add_option('github_username', '');
            add_option('github_repository', '');
            add_option('github_access_token', '');
            add_option('github_event_type', ''); // Initialize the event type option
        }
    }
    
}