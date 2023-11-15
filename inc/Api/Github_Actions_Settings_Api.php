<?php
/**
 * @package GithubActions
*/

namespace GAP\Api;
 
if( ! class_exists('Github_Actions_Settings_Api')){

    class Github_Actions_Settings_Api{

        public static $admin_pages = array();

        public static $admin_sub_pages = array();

        public static function register(){
            if(! empty(self::$admin_pages)){
                add_action('admin_menu', array(__CLASS__, 'addAdminMenu'));
            }
        }

        public static function addPages(array $pages){
           self::$admin_pages = $pages;
           return new static();
        }

        public static function addSubPages(array $pages){
            self::$admin_sub_pages = array_merge(self::$admin_sub_pages, $pages);
            return new static();
        }

        public static function withSubPage(string $title = null){
            if(empty (self::$admin_pages)){
                return new static();
            }
            $admin_page = self::$admin_pages[0];

            $sub_page = [
                [
                'parent_slug' => $admin_page['menu_slug'],
                'page_title' => $admin_page['page_title'],
                'menu_title' => ($title) ? $title : $admin_page['menu_title'],
                'capability' => $admin_page['capability'],
                'menu_slug' => $admin_page['menu_slug'],
                'callback' => $admin_page['callback']
                ]
            ];

            self::$admin_sub_pages = $sub_page;

            return new static();
        }

        public static function addAdminMenu() {
            
            foreach (self::$admin_pages as $page) {
                add_menu_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position']);
            }

            foreach (self::$admin_sub_pages as $page) {
                add_submenu_page($page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback']);
            }
            
        }

        public static function registerCustomFields(){
            //Register settings
            register_setting( $setting['option_group'], $setting['option_name'], (isset ($setting['callback']) ? $setting['callback'] : '') );
            //Add settings section
            add_settings_section( $section['id'], $section['title'], (isset ($section['callback']) ? $section['callback'] : ''), $section['page'] );
            // Add settings field
            add_settings_field( $field['id'], $field['title'], (isset ($field['callback']) ? $field['callback'] : ''), $field['page'], $field['section'], (isset ($field['args']) ? $field['args'] : '') );
            
        }
        
    }
    
}