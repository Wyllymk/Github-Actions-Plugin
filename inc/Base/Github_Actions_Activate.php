<?php
/**
 * @package GithubActions
*/

namespace GAP\Base;
 
if( ! class_exists('Github_Actions_Activate')){

    class Github_Actions_Activate{

        public static function activate(){
            flush_rewrite_rules();
            self::githubActionsTriggerDatabase(); // Call the initialization method
        }

        public static function githubActionsTriggerDatabase() {
            add_option('github_username', '');
            add_option('github_access_token', '');
            add_option('repository_name', '');
            add_option('repository_branch', ''); 
        }
        
    }
    
}