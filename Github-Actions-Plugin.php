<?php
/**
 * @package GithubActions
 */
/**
 * Plugin Name: Github Actions
 * Plugin URI: https://github.com/Wyllymk/Github-Actions-Plugin/
 * Description: A WordPress plugin to input GitHub credentials and trigger GitHub Actions workflow.
 * Version: 0.1.0
 * Author: Wilson
 * Author URI: https://atomicwebspace.com/
 * License: GPLv2 or later
 * Text Domain: github-actions 
*/

if( ! defined('ABSPATH')){
    die;
}

// Define Constants
define( 'GITHUB_ACTIONS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'GITHUB_ACTIONS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GITHUB_ACTIONS_PLUGIN_NAME', plugin_basename(__FILE__) );

if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'vendor/autoload.php')){
    require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'vendor/autoload.php');
}

/**
 * The function "activate_externally" activates the "Github_Actions_Activate" class externally.
 */
function activateGithubActionsExternally(){
    GAP\Base\Github_Actions_Activate::activate();
}

// The function is used to register a callback function that will be executed when the plugin is activated. 
register_activation_hook(__FILE__, 'activateGithubActionsExternally');

/**
 * The function "deactivate_externally" calls the "deactivate" method of the
 * "Github_Actions_Deactivate" class.
 */
function deactivateGithubActionsExternally(){
    GAP\Base\Github_Actions_Deactivate::deactivate();
}

// The function is used to register a callback function that will be executed when the plugin is deactivated. 
register_deactivation_hook(__FILE__, 'deactivateGithubActionsExternally');

/* Checking if the class exists and if it does, it will register the services. 
* This is a way to ensure that the class is loaded before calling its methods, preventing any errors or issues. 
*/
if(class_exists('GAP\\Github_Actions_Init')){
    GAP\Github_Actions_Init::registerServices();
}

add_action( 'init', 'github_plugin_updater_test_init' );
function github_plugin_updater_test_init() {

	include_once 'Github-Actions-Update.php';

	define( 'WP_GITHUB_FORCE_UPDATE', true );

	if ( is_admin() ) { // note the use of is_admin() to double check that this is happening in the admin

		$config = array(
			'slug' => GITHUB_ACTIONS_PLUGIN_NAME,
			'proper_folder_name' => dirname( GITHUB_ACTIONS_PLUGIN_NAME ),
			'api_url' => 'https://api.github.com/repos/Wyllymk/Github-Actions-Plugin',
			'raw_url' => 'https://raw.github.com/Wyllymk/Github-Actions-Plugin/production',
			'github_url' => 'https://github.com/Wyllymk/Github-Actions-Plugin',
			'zip_url' => 'https://github.com/Wyllymk/Github-Actions-Plugin/releases/download/v0.1.0/Github-Actions-Plugin.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);

		new WP_GitHub_Updater( $config );

	}

}