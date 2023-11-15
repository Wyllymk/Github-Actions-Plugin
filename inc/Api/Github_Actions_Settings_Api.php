<?php
/**
 * @package GithubActions
*/

namespace GAP\Api;
 
if( ! class_exists('Github_Actions_Settings_Api')){

    class Github_Actions_Settings_Api{

        private static $admin_pages = array();

        public static function register(){
            if(! empty(self::$admin_pages)){
                add_action('admin_menu', array(__CLASS__, 'addAdminMenu'));
            }
        }

        public static function addPages(array $pages){
           self::$admin_pages = $pages;
           return new self();
        }

        public static function addAdminMenu() {
            foreach (self::$admin_pages as $page) {
                add_menu_page($page['page_title'], $page['menu_title'], $page['compatibility'], $page['menu_slug'], $page['callback'], $page['con_url'], $page['position']);
            }
        }
        
    }
    
}