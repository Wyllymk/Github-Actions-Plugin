<?php
/**
 * @package GithubActions
*/

namespace GAP\Theme;

if( ! class_exists('Github_Actions_Trigger_Workflow')){

    class Github_Actions_Trigger_Workflow{
        public static function register(){
            // Register AJAX actions
            add_action('wp_ajax_trigger_workflow', array(__CLASS__, 'triggerWorkflow')); 
        }
        
        public static function triggerWorkflow() {
            if (!current_user_can('manage_options')) {
                wp_die('Unauthorized');
            }
    
            $github_username = sanitize_text_field($_POST['github_username']);
            $github_access_token = sanitize_text_field($_POST['github_access_token']);
            $repository_name = sanitize_text_field($_POST['repository_name']);
            $repository_branch = sanitize_text_field($_POST['repository_branch']); 
    
            // Trigger the GitHub Actions Workflow using $github_username, $github_access_token, $repository_name, and  $repository_name
            $repository_owner = $github_username;
            $github_repository_name = $repository_name;
            $repository_reference = $repository_branch;
    
            $url = "https://api.github.com/repos/{$repository_owner}/{$github_repository_name}/zipball/{$repository_reference}";
            $headers = array(
                'Authorization: Bearer ' . $github_access_token,
                'User-Agent: Github Actions Trigger',
                'X-GitHub-Api-Version: 2022-11-28',
                "Accept: application/vnd.github+json"
            );

            $curl = curl_init($url);
            
            // Set cURL options
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

            // Set up progress callback
            curl_setopt($curl, CURLOPT_NOPROGRESS, false);
            curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, function ($ch, $downloadSize, $downloaded, $uploadSize, $uploaded) {
                if ($downloadSize > 0) {
                    $progress = round(($downloaded / $downloadSize) * 100);
                    echo "Downloading: {$progress}%\n";
                    ob_flush();
                    flush();
                    
                    // Store the progress in the session and write/close the session
                    session_start();
                    $_SESSION['download_progress'] = $progress;
                    session_write_close();
                }
                return 0;
            });
    
            // Execute the cURL session and store the response
            $response = curl_exec($curl);

            // Check for cURL errors
            if (curl_errno($curl)) {
                echo 'cURL error: ' . curl_error($ch);
                exit;
            }

            // Check the HTTP status code of the response
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($http_status !== 200) {
                echo 'GitHub API returned a non-200 status code: ' . $http_status;
                exit;
            }

            // Close the cURL session
            curl_close($curl);

            // Define the directory where you want to save the ZIP archive
            // Get the WordPress uploads directory information
            $upload_dir = wp_upload_dir();

            // Define the path to save the ZIP archive in the uploads directory
            $zip_file = $upload_dir['path'] . '/theme-archive.zip';

            // Make sure the directory exists, create it if necessary
            if (!file_exists($upload_dir['path'])) {
                mkdir($upload_dir['path'], 0755, true);
            }

            // Now you can save the ZIP archive to the uploads directory
            file_put_contents($zip_file, $response);
            
            $extractionMessage = '';
            $activationMessage = '';
            
            // Unzip the repository to the WordPress themes directory
            $theme_directory = get_theme_root();
            $zip = new ZipArchive;
            if ($zip->open($zip_file) === TRUE) {
                // Get the first entry in the ZIP file (assuming it's the repository name)
                $first_entry = $zip->getNameIndex(0);

                // Define the destination folder with the repository name
                $destination_folder = $theme_directory . '/' . $first_entry;

                // Extract the contents to the destination folder
                if ($zip->extractTo($theme_directory) && $zip->close()) {
                    $extractionMessage = 'ZIP archive has been extracted successfully.';

                    // Activate the theme in WordPress
                    $theme_name = basename($first_entry);
                    // Check if the theme exists
                    if (wp_get_theme($theme_name)->exists()) {
                        // Activate the theme using the "stylesheet" name
                        switch_theme($theme_name);
                        $activationMessage =  'Theme activated successfully.';
                    } else {
                        $activationMessage = 'Failed to activate the theme. The theme folder does not exist.';
                    }

                    // Optional: Delete the ZIP file if you no longer need it
                    unlink($zip_file);
                }else {
                    $extractionMessage = 'Failed to extract the ZIP archive or close the ZipArchive.';
                }
            } else {
                $extractionMessage = 'Failed to open the ZIP archive.';
            }

            // Optional: Delete the ZIP file if you no longer need it
            unlink($zip_file);
    
            wp_die();
        }
    }

}