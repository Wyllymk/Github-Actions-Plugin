<?php
/**
 * @package GithubActions
*/

namespace GAP\Base;
 
if( ! class_exists('Github_Actions_Activate')){

    class Github_Actions_Activate{

        public static function activate(){
            flush_rewrite_rules();
        }

    }
    
}