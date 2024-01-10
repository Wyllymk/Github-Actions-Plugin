<?php
/**
 * @package GithubActions
*/

namespace GAP\Api;
 
if( ! class_exists('Github_Actions_Callbacks')){

    class Github_Actions_Callbacks{

        public static function adminDashboard(){
            if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_dashboard.php')){
                require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_dashboard.php');
            }
        }
        public static function adminTheme(){
            if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_themes.php')){
                require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_themes.php');
            }
        }
        public static function adminPlugin(){
            if(file_exists(GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_plugins.php')){
                require_once (GITHUB_ACTIONS_PLUGIN_PATH . 'templates/github_actions_plugins.php');
            }
        }
        public static function githubOptionsGroup($input){
            return $input;
        }
        public static function githubAdminSection(){
            echo 'Please fill in the fields below!';
        }
        public static function githubAdminSection2(){
            echo 'Please fill in the field below!';
        }
        public static function githubToken() {
            $options = get_option('github_options_defaults');
        
            // Check if the 'github_access_token' key exists in the $options array
            $github_access_token = isset($options['github_access_token']) ? esc_attr($options['github_access_token']) : '';
        
            echo '<input type="text" class="regular-text" name="github_options_defaults[github_access_token]" value="' . $github_access_token . '" placeholder="Enter Github Access Token">';
        }
        
        public static function githubUserName(){
            $options = get_option('themes_github_options');
            $ga_username = isset($options['ga_username']) ? esc_attr($options['ga_username']) : '';
            echo '<input type="text" class="regular-text" name="themes_github_options[ga_username]" value="'. $ga_username . '" placeholder="Enter Github UserName">';
        }
        public static function githubRepositoryName(){
            $options = get_option('themes_github_options');
            $ga_theme_repository_name = isset($options['ga_theme_repository_name']) ? esc_attr($options['ga_theme_repository_name']) : '';
            echo '<input type="text" class="regular-text" name="themes_github_options[ga_theme_repository_name]" value="'. $ga_theme_repository_name . '" placeholder="Enter Theme Repository Name">';
        }  
        public static function githubRepositoryBranch(){
            $options = get_option('themes_github_options');
            $ga_theme_repository_branch = isset($options['ga_theme_repository_branch']) ? esc_attr($options['ga_theme_repository_branch']) : '';
            $default_branch_text = 'If left empty, the default branch will be "main"';

            echo '<input type="text" class="regular-text" name="themes_github_options[ga_theme_repository_branch]" value="'. $ga_theme_repository_branch . '" placeholder="Enter Repository Branch">';
            echo '<p class="description">' . esc_html($default_branch_text) . '</p>';

        }
        public static function githubPrivateRepository() {
            $options = get_option('themes_github_options');
            $ga_private_theme = isset($options['ga_private_theme']) ? esc_attr($options['ga_private_theme']) : '0';
            $default_repo_text = 'Click if the repository is set to private';
        
            echo '<input type="checkbox" class="regular-text" name="themes_github_options[ga_private_theme]" value="1" ' . checked(1, $ga_private_theme, false) . '>';
            echo '<p class="description">' . esc_html($default_repo_text) . '</p>';
        }
        
        
        
        
    }
    
}