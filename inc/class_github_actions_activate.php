<?php
/**
 * @package GithubActions
 */

class Github_Actions_Activate{
    public static function activate(){
        flush_rewrite_rules();
    }
}