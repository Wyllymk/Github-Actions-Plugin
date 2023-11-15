<?php
/**
 * @package GithubActions
*/

namespace GAP\Pages;

use \GAP\Api\Github_Actions_Settings_Api;
use \GAP\Theme\Github_Actions_Trigger_Workflow;
use \GAP\Theme\Github_Actions_Save_Settings;

if( ! class_exists('Github_Actions_Admin')){

    class Github_Actions_Admin{
                
        public static function register(){
        
            add_action( 'admin_menu', array(__CLASS__, 'adminPages') );

            /* Checking if the class exists and if it does, it will register the services. 
            * This is a way to ensure that the class is loaded before calling its methods, preventing any errors or issues. 
            */
            if(class_exists('Github_Actions_Save_Settings')){
                Github_Actions_Save_Settings::register();
            }

            /* Checking if the class exists and if it does, it will register the services. 
            * This is a way to ensure that the class is loaded before calling its methods, preventing any errors or issues. 
            */
            if(class_exists('Github_Actions_Trigger_Workflow')){
                Github_Actions_Trigger_Workflow::register();
            }
        }

        public static function adminPages(){
            add_menu_page('Github Actions Trigger', 'Github Actions', 'manage_options', 'github-actions-trigger', array(__CLASS__, 'githubActionsPage'), 'dashicons-admin-site-alt', 110);
        }

        public static function githubActionsPage(){
            if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_page.php')){
                require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_page.php');
            }
        }

        

    }
    
}