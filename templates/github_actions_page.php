<?php

$github_username = get_option('github_username', '');
$github_access_token = get_option('github_access_token', '');
$repository_name = get_option('repository_name', '');
$repository_branch = get_option('repository_branch', ''); 

?>

<div class="container position-relative d-flex flex-column justify-content-around">
    <div class="d-flex justify-content-center align-items-center mt-5">
        <img src="<?php echo GITHUB_ACTIONS_PLUGIN_URL. 'assets/img/icons8-github-96.png' ?>" alt="Github Actions Logo">
        <h1 class="ms-5">Github Actions Trigger</h1>
    </div>
    <div class="container-fluid mt-5">

        <div class="container">
            <form action="" method="post" class="container d-flex flex-column mx-5">
                <h3>Enter your Credentials here:</h3>
                <div class="my-3 d-flex justify-content-between">
                    <label for="github-username" class="form-label">GitHub Username:</label>
                    <input type="text" class="form-control-lg" id="github-username" name="github_username"
                        value="<?php echo esc_attr($github_username); ?>" placeholder="Enter GitHub Username"
                        aria-describedby="usernameHelp" required>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <label for="github-access-token" class="form-label">GitHub Access Token</label>
                    <input type="text" class="form-control-lg" id="github-access-token" name="github_access_token"
                        value="<?php echo esc_attr($github_access_token); ?>" placeholder="Enter GitHub Access Token"
                        required>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <label for="repository-name" class="form-label">Repository Name:</label>
                    <input type="text" class="form-control-lg" id="repository-name" name="repository_name"
                        value="<?php echo esc_attr($repository_name); ?>" placeholder="Enter Repository Name" required>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <label for="repository-branch" class="form-label">Repository Branch:</label>
                    <input type="text" class="form-control-lg" id="repository-branch" name="repository_branch"
                        value="<?php echo esc_attr($repository_branch); ?>" placeholder="Enter Repository Branch"
                        required>
                </div>
                <div class="my-3 mx-5 d-flex justify-content-between">
                    <input type="button" class="btn btn-primary btn-lg rounded-0" value="Save Settings"
                        onclick="saveSettings()">
                    <input type="button" class="btn btn-primary btn-lg rounded-0" value="Trigger Workflow"
                        onclick="triggerWorkflow()">
                </div>
            </form>
            <div id="response"></div>
        </div>
        <div class="container mt-5">
            <h3>Entered Credentials:</h3>
            <table class="my-3 table table-hover">
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
                        <td id="github_javascript_username"><?php echo esc_html($github_username); ?></td>
                        <td><?php echo esc_html($github_access_token); ?></td>
                        <td><?php echo esc_html($repository_name); ?></td>
                        <td><?php echo esc_html($repository_branch); ?></td>
                    </tr>
                </tbody>
            </table>

        </div>

    </div>
    <div class="fixed-bottom d-flex flex-row justify-content-center">
        <p>Copyright &copy; <?php echo date('Y'); ?> <a class="" target="_blank"
                href="https://atomicwebspace.com">Wilson</a></p>
    </div>
</div>