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

        public static $settings;

        public static $pages = array();

        public static $sub_pages = array();
       
        public static function register(){

            self::$callbacks = new Github_Actions_Callbacks();

            self::setPages();

            self::setSubPages();

            self::$settings = new Github_Actions_Settings_Api();
        
            self::$settings->addPages(self::$pages)->withSubPage('Dashboard')->addSubPages(self::$sub_pages)->register();

        }
        
        public static function setPages(){
            self::$pages = [
                [
                'page_title' => 'Github Actions Trigger',
                'menu_title' => 'Github Actions',
                'capability' => 'manage_options',
                'menu_slug' => 'github-actions-trigger',
                'callback' => [self::$callbacks, 'adminDashboard'],
                'icon_url' => 'dashicons-admin-site-alt',
                'position' => 110
                ],
            ];
        }

        public static function setSubPages(){
            self::$sub_pages = [
                [
                    'parent_slug' => 'github-actions-trigger',
                    'page_title' => 'Github Actions Themes',
                    'menu_title' => 'Themes',
                    'capability' => 'manage_options',
                    'menu_slug' => 'github-actions-themes',
                    'callback' => function(){ echo '<h1>Something is missing</h1>';},
                ],
                [
                    'parent_slug' => 'github-actions-trigger',
                    'page_title' => 'Github Actions Plugins',
                    'menu_title' => 'Plugins',
                    'capability' => 'manage_options',
                    'menu_slug' => 'github-actions-plugins',
                    'callback' => function(){ echo '<h1>Something is missing</h1>';},
                ],
            ];
        }

    }
    
}