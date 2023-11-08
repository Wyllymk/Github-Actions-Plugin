<?php
/**
 * @package GithubActions
 */
/**
 * Plugin Name: Github Actions
 * Plugin URI: #
 * Description: A WordPress plugin to input GitHub credentials and trigger GitHub Actions workflow.
 * Version: 1.0.1
 * Author: Wilson
 * Author URI: #
 * License: GPLv2 or later
 * Text Domain: github-actions 
*/

if( ! defined('ABSPATH')){
    die;
}

define( 'GITHUB_ACTIONS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'GITHUB_ACTIONS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/* Checking if the file exists and if it does, it will require it. */
if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'inc/class_github_actions_activate.php')){
    require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'inc/class_github_actions_activate.php');
}
if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'inc/class_github_actions_deactivate.php')){
    require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'inc/class_github_actions_deactivate.php');
}
if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'inc/class_github_actions_init.php')){
    require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'inc/class_github_actions_init.php');
}

function activate_externally(){
    Github_Actions_Activate::activate();
}


function deactivate_externally(){
    Github_Actions_Deactivate::deactivate();
}

register_activation_hook(__FILE__, 'activate_externally');
register_deactivation_hook(__FILE__, 'deactivate_externally');

/* Checking if the class exists and if it does, it will register the services. */
if(class_exists('Github_Actions_Init')){
    $github_actions = new Github_Actions_Init();
    $github_actions->register_services();
}