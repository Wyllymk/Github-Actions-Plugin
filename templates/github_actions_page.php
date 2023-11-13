<?php

$github_username = get_option('github_username', '');
$github_access_token = get_option('github_access_token', '');
$repository_name = get_option('repository_name', '');
$repository_branch = get_option('repository_branch', ''); 

?>

<div class="gat-main-container">
    <div class="gat-header">
        <img src="<?php echo GITHUB_ACTIONS_PLUGIN_URL. 'assets/img/icons8-github-96.png' ?>" alt="Github Actions Logo">
        <h1 class="ms-5">Github Actions Trigger</h1>
    </div>
    <div class="gat-body">

        <div class="gat-container">

            <form action="" method="post" class="gat-form">

                <h3>Enter your Credentials here:</h3>
                <div class="gat-col">
                    <label for="github-username" class="form-label">GitHub Username:</label>
                    <input type="text" class="form-control-lg" id="github-username" name="github_username"
                        value="<?php echo esc_attr($github_username); ?>" placeholder="Enter GitHub Username" required>
                </div>
                <div class="gat-col">
                    <label for="github-access-token" class="form-label">GitHub Access Token:</label>
                    <input type="text" class="form-control-lg" id="github-access-token" name="github_access_token"
                        value="<?php echo esc_attr($github_access_token); ?>" placeholder="Enter GitHub Access Token"
                        required>
                </div>
                <div class="gat-col">
                    <label for="repository-name" class="form-label">Repository Name:</label>
                    <input type="text" class="form-control-lg" id="repository-name" name="repository_name"
                        value="<?php echo esc_attr($repository_name); ?>" placeholder="Enter Repository Name" required>
                </div>
                <div class="gat-col">
                    <label for="repository-branch" class="form-label">Repository Branch:</label>
                    <input type="text" class="form-control-lg" id="repository-branch" name="repository_branch"
                        value="<?php echo esc_attr($repository_branch); ?>" placeholder="Enter Repository Branch"
                        required>
                </div>
                <div class="gat-col-buttons">
                    <input type="button" id="save-button" class="button-primary" value="Save Settings"
                        onclick="saveSettings()">
                    <input type="button" id="trigger-button" class="button-primary" value="Trigger Workflow"
                        onclick="triggerWorkflow()">
                </div>

            </form>

            <div id="response"></div>

            <label for="file">Downloading progress:</label>
            <progress id="progress-bar" value="0" max="100"></progress>

            <div class="lds-spinner" id="loading-spinner">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="gat-container">
            <h3 class="gat-text">Entered Credentials:</h3>
            <table class="gat-table">
                <thead>
                    <tr class="table-active">
                        <th scope="col">#</th>
                        <th scope="col">GitHub Username</th>
                        <th scope="col">Github Access Token</th>
                        <th scope="col">Repository Name</th>
                        <th scope="col">Repository Branch</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td id="github-javascript-username"><?php echo esc_html($github_username); ?></td>
                        <td id="github-javascript-token"><?php echo esc_html($github_access_token); ?></td>
                        <td id="github-javascript-name"><?php echo esc_html($repository_name); ?></td>
                        <td id="github-javascript-branch"><?php echo esc_html($repository_branch); ?></td>
                    </tr>
                </tbody>
            </table>

        </div>

    </div>
    <div class="gat-footer">
        <div>
            <p>Copyright &copy; <?php echo date('Y'); ?> <a class="" target="_blank"
                    href="https://atomicwebspace.com">Wilson</a></p>
        </div>
    </div>
</div>