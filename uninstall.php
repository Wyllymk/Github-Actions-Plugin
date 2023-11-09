<?php
/**
 * Trigger this file on plugin uninstall
 * 
 * @package GithubActions
 */

 if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ){
     exit;
 }

 // Clean up
delete_option('github_username');
delete_option('github_access_token');
delete_option('repository_name');
delete_option('repository_branch');