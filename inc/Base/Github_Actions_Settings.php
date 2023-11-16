<?php
/**
 * @package GithubActions
*/

namespace GAP\Base;
 
if( ! class_exists('Github_Actions_Settings')){

    class Github_Actions_Settings{

        public static function register(){
            add_filter( 'plugin_action_links_'.GITHUB_ACTIONS_PLUGIN_NAME, array(__CLASS__, 'settingsLink') );
        }
        
        public static function settingsLink($links){
            $settings_link = '<a href="admin.php?page=github-actions-trigger">Settings</a>';
            array_push( $links, $settings_link);
            return $links;
        }    
        
    }
    
}