<?php
/**
 * @package             GithubActions
 * @author              Wilson
 * @copyright           2024 Kabatha
 * 
 * @wordpress-plugin
 * Plugin Name:             Github Actions
 * Plugin URI:              https://github.com/Wyllymk/Github-Actions-Plugin/
 * Description:             A WordPress plugin to input GitHub credentials and trigger GitHub Actions workflow.
 * Version:                 0.1.1
 * Requires at least:       6.0
 * Requires PHP:            7.2
 * Author:                  Wilson
 * Author URI:              https://wilsondevops.com/
 * License:                 GPLv2 or later
 * License URI:             https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:              https://example.com/my-plugin/
 * Text Domain:             github-actions 
 * Domain Path:             /languages

*/

/*
Github Actions is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Github Actions is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Github Actions. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if( ! defined('ABSPATH')){
    die;
}

// Define Constants
define( 'GITHUB_ACTIONS_PLUGIN_VERSION', '0.1.0');
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