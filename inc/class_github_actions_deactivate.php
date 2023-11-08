<?php
/**
 * @package GithubActions
 */
if( ! class_exists('Github_Actions_Deactivate')){

    class Github_Actions_Deactivate{
        public static function deactivate(){
            flush_rewrite_rules();
            echo 'hello';
        }
    }
    
}