<?php
/**
 * @package GithubActions
*/

namespace GAP\Base;
 
if( ! class_exists('Github_Actions_Enqueue')){

    class Github_Actions_Enqueue{

        public static function register(){
            add_action( 'admin_enqueue_scripts', array(__CLASS__, 'enqueueAdminScripts') );
        }

        public static function enqueueAdminScripts(){
            wp_enqueue_script('github-actions-script', GITHUB_ACTIONS_PLUGIN_URL . 'assets/js/github-actions.js');
            wp_enqueue_style('github-actions-style', GITHUB_ACTIONS_PLUGIN_URL . 'assets/css/github-actions.css');
        }
        
    }
    
}