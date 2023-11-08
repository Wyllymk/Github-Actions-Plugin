<?php
/**
 * @package GithubActions
*/
if( ! class_exists('Github_Actions_Init')){

    class Github_Actions_Init{
        public static function register_services(){
            add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_admin_scripts'));
        }

        public static function enqueue_admin_scripts(){
            wp_enqueue_script('github-actions-script', GITHUB_ACTIONS_PLUGIN_URL . 'assets/js/github-actions.js');
            wp_enqueue_style('github-actions-style', GITHUB_ACTIONS_PLUGIN_URL . 'assets/css/github-actions.css');
        }
    }
    
}