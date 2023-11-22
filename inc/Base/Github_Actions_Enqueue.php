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
            wp_enqueue_style('github-actions-style', GITHUB_ACTIONS_PLUGIN_URL . 'assets/css/github-actions.css');
            wp_enqueue_script('github-actions-script', GITHUB_ACTIONS_PLUGIN_URL . 'assets/js/github-actions.js', array( 'jquery' ), '1.0.0', array('in_footer' => true, 'strategy' => 'defer',));
           // Pass AJAX URL to the script
           $title_nonce = wp_create_nonce( 'github_actions_theme_nonce' );
            wp_localize_script('github-actions-script', 'workflowAjax', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => $title_nonce,));
        }
        
    }
    
}