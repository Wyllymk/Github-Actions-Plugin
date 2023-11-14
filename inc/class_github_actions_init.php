<?php
/**
 * @package GithubActions
*/

if( ! class_exists('Github_Actions_Init')){

    class Github_Actions_Init{
                
        public static function register_services(){
        
            add_action( 'admin_enqueue_scripts', array(__CLASS__, 'enqueue_admin_scripts') );
            add_action( 'admin_menu', array(__CLASS__, 'admin_pages') );
            add_filter( 'plugin_action_links_'.GITHUB_ACTIONS_PLUGIN_NAME, array(__CLASS__, 'settings_link') );

            /* Checking if the file exists and if it does, it will require it. */
            if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'inc/theme/class_github_actions_save_settings.php')){
                require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'inc/theme/class_github_actions_save_settings.php');
            }

            /* Checking if the class exists and if it does, it will register the services. 
            * This is a way to ensure that the class is loaded before calling its methods, preventing any errors or issues. 
            */
            if(class_exists('Github_Actions_Save_Settings')){
                Github_Actions_Save_Settings::register_services();
            }
            
             /* Checking if the file exists and if it does, it will require it. */
             if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'inc/theme/class_github_actions_trigger_workflow.php')){
                require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'inc/theme/class_github_actions_trigger_workflow.php');
            }

            /* Checking if the class exists and if it does, it will register the services. 
            * This is a way to ensure that the class is loaded before calling its methods, preventing any errors or issues. 
            */
            if(class_exists('Github_Actions_Trigger_Workflow')){
                Github_Actions_Trigger_Workflow::register_services();
            }
        }

        public static function enqueue_admin_scripts(){
            wp_enqueue_script('github-actions-script', GITHUB_ACTIONS_PLUGIN_URL . 'assets/js/github-actions.js');
            wp_enqueue_style('github-actions-style', GITHUB_ACTIONS_PLUGIN_URL . 'assets/css/github-actions.css');
        }

        public static function admin_pages(){
            add_menu_page('Github Actions Trigger', 'Github Actions', 'manage_options', 'github-actions-trigger', array(__CLASS__, 'github_actions_page'), 'dashicons-admin-site-alt', 110);
        }

        public static function github_actions_page(){
            if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_page.php')){
                require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_page.php');
            }
        }

        public static function settings_link($links){
            $settings_link = '<a href="admin.php?page=github-actions-trigger">Settings</a>';
            array_push( $links, $settings_link);
            return $links;
        }

    }
    
}