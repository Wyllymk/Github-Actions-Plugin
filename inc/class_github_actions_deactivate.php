<?php
/**
 * @package GithubActions
 */

class Github_Actions_Deactivate{
    public static function deactivate(){
        flush_rewrite_rules();
        echo 'hello';
    }
}