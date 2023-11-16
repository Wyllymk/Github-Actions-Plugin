<?php
/**
 * @package GithubActions
*/

namespace GAP\Api;
 
if( ! class_exists('Github_Actions_Callbacks')){

    class Github_Actions_Callbacks{

        public static function register(){
            
        }

        public static function adminDashboard(){
            if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_page.php')){
                require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_page.php');
            }
        }
        
    }
    
}