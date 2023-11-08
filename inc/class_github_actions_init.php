<?php
/**
 * @package GithubActions
*/

class Github_Actions_Init{
    public function register_services(){
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function enqueue_admin_scripts(){
        wp_enqueue_script('github-actions-script', GITHUB_ACTIONS_PLUGIN_URL . 'assets/js/github-actions.js');
        wp_enqueue_style('github-actions-style', GITHUB_ACTIONS_PLUGIN_URL . 'assets/css/github-actions.css');
    }
}