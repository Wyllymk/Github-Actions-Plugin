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

/* The code is defining a constant called `GITHUB_ACTIONS_PLUGIN_PATH` with the value of the plugin directory path. */
define( 'GITHUB_ACTIONS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/* The code is defining a constant called `GITHUB_ACTIONS_PLUGIN_URL` with the value of the plugin directory URL. */
define( 'GITHUB_ACTIONS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/* The code is defining a constant called `GITHUB_ACTIONS_PLUGIN_NAME` with the value of the plugin's basename. */
define( 'GITHUB_ACTIONS_PLUGIN_NAME', plugin_basename(__FILE__) );

/**
 * The function "activate_externally" activates the "Github_Actions_Activate" class externally.
 */
function activate_github_actions_externally(){
    /* Checking if the file exists and if it does, it will require it. */
    if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'inc/class_github_actions_activate.php')){
        require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'inc/class_github_actions_activate.php');
    }
    Github_Actions_Activate::activate();
}

/* The function is used to register a callback function that will be executed when the plugin is activated. 
In this case, the `activate_externally` function is registered as the callback function. */
register_activation_hook(__FILE__, 'activate_github_actions_externally');

/**
 * The function "deactivate_externally" calls the "deactivate" method of the
 * "Github_Actions_Deactivate" class.
 */
function deactivate_github_actions_externally(){
    /* Checking if the file exists and if it does, it will require it. */
    if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'inc/class_github_actions_deactivate.php')){
        require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'inc/class_github_actions_deactivate.php');
    }
    Github_Actions_Deactivate::deactivate();
}

/* The function is used to register a callback function that will be executed when the plugin is deactivated. 
In this case, the `deactivate_externally` function is registered as the callback function. */
register_deactivation_hook(__FILE__, 'deactivate_github_actions_externally');

/* Checking if the file exists and if it does, it will require it. */
if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'inc/class_github_actions_init.php')){
    require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'inc/class_github_actions_init.php');
}

/* Checking if the class exists and if it does, it will register the services. 
* This is a way to ensure that the class is loaded before calling its methods, preventing any errors or issues. 
*/
if(class_exists('Github_Actions_Init')){
    Github_Actions_Init::register_services();
}