<?php
/**
 * @package GithubActions
*/

namespace GAP\Pages;

use \GAP\Api\Github_Actions_Callbacks;
use \GAP\Api\Github_Actions_Settings_Api;
use \GAP\Theme\Github_Actions_Save_Settings;
use \GAP\Theme\Github_Actions_Trigger_Workflow;


if( ! class_exists('Github_Actions_Admin')){

    class Github_Actions_Admin{

        public static $callbacks;

        public static $admin_settings;

        public static $pages = array();

        public static $sub_pages = array();
       
        public static function register(){

            self::$callbacks = new Github_Actions_Callbacks();

            self::$admin_settings = new Github_Actions_Settings_Api();

            self::setPages();

            self::setSubPages();

            self::setAdminSettings();

            self::setAdminSections();

            self::setAdminFields();            
        
            self::$admin_settings->addPages(self::$pages)->withSubPage('Dashboard')->addSubPages(self::$sub_pages)->register();

        }
        
        public static function setPages(){
            self::$pages = [
                [
                'page_title'    => 'Github Actions Trigger',
                'menu_title'    => 'Github Actions',
                'capability'    => 'manage_options',
                'menu_slug'     => 'github-actions-trigger',
                'callback'      => [self::$callbacks, 'adminDashboard'],
                'icon_url'      => 'dashicons-admin-site-alt',
                'position'      => 110
                ],
            ];
        }

        public static function setSubPages(){
            self::$sub_pages = [
                [
                    'parent_slug'   => 'github-actions-trigger',
                    'page_title'    => 'Github Actions Themes',
                    'menu_title'    => 'Themes',
                    'capability'    => 'manage_options',
                    'menu_slug'     => 'github-actions-themes',
                    'callback'      => [self::$callbacks, 'adminTheme'],
                ],
                [
                    'parent_slug'   => 'github-actions-trigger',
                    'page_title'    => 'Github Actions Plugins',
                    'menu_title'    => 'Plugins',
                    'capability'    => 'manage_options',
                    'menu_slug'     => 'github-actions-plugins',
                    'callback'      => [self::$callbacks, 'adminPlugin'],
                ],
            ];
        }

        public static function setAdminSettings(){
            $args = array(
                array(
                    'option_group'  => 'github_actions_admin_group',
                    'option_name'   => 'github_options_defaults'
                ),
                array(
                    'option_group'  => 'github_actions_theme_group',
                    'option_name'   => 'themes_github_options',
                    'callback'      => [self::$callbacks, 'githubOptionsGroup'],
                ),
                array(
                    'option_group'  => 'github_actions_plugin_group',
                    'option_name'   => 'plugins_github_options',
                    'callback'      => [self::$callbacks, 'githubOptionsGroup'],
                ),     

            );
            self::$admin_settings->setSettings($args);
        }

        public static function setAdminSections(){
            $args = array(
                array(
                    'id'            => 'github_actions_index_token',
                    'title'         => 'Github Settings',
                    'callback'      => [self::$callbacks, 'githubAdminSection2'],
                    'page'          => 'github-actions-trigger'
                ),
                array(
                    'id'            => 'github_actions_index',
                    'title'         => 'Theme Settings',
                    'callback'      => [self::$callbacks, 'githubAdminSection'],
                    'page'          => 'github-actions-theme'
                ),
                
            );
            self::$admin_settings->setSections($args);
        }

        public static function setAdminFields(){
            $args = array(
                    array(
                    'id'            => 'github_actions_token',
                    'title'         => 'Github Token',
                    'callback'      => [self::$callbacks, 'githubToken'],
                    'page'          => 'github-actions-trigger',
                    'section'       => 'github_actions_index_token',
                    'args'          => array(
                        'label_for' => 'github_actions_token',
                        'type'      => 'text',
                        'class'     => 'example-text',
                        )
                    ),
                    
                    array(
                        'id'            => 'ga_username',
                        'title'         => 'Github UserName',
                        'callback'      => [self::$callbacks, 'githubUserName'],
                        'page'          => 'github-actions-theme',
                        'section'       => 'github_actions_index',
                        'args'          => array(
                            'label_for' => 'ga_username',
                            'type'      => 'text',
                            'class'     => 'example-text',
                        )
                    ),
                    array(
                        'id'            => 'ga_theme_repository_name',
                        'title'         => 'Repository Name',
                        'callback'      => [self::$callbacks, 'githubRepositoryName'],
                        'page'          => 'github-actions-theme',
                        'section'       => 'github_actions_index',
                        'args'          => array(
                            'label_for' => 'ga_theme_repository_name',
                            'type'      => 'text',
                            'class'     => 'example-text',
                        )
                    ),
                    array(
                        'id'            => 'ga_theme_repository_branch',
                        'title'         => 'Repository Branch',
                        'callback'      => [self::$callbacks, 'githubRepositoryBranch'],
                        'page'          => 'github-actions-theme',
                        'section'       => 'github_actions_index',
                        'args'          => array(
                            'label_for' => 'ga_theme_repository_branch',
                            'type'      => 'text',
                            'class'     => 'example-text',
                        )
                    ),
                    array(
                        'id'            => 'ga_private_theme',
                        'title'         => 'Private Repository',
                        'callback'      => [self::$callbacks, 'githubPrivateRepository'],
                        'page'          => 'github-actions-theme',
                        'section'       => 'github_actions_index',
                        'args'          => array(
                            'label_for' => 'ga_private_theme',
                            'type'      => 'text',
                            'class'     => 'example-text',
                        )
                    ),
                    
            );
            self::$admin_settings->setFields($args);
        }

    }
    
}