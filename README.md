# Github-Actions-Plugin

A WordPress plugin to input GitHub credentials and trigger GitHub Actions workflow.

- Plugin to trigger Github actions via https request from WordPress Dashboard

Full list of sections and features incorporated in the Github Actions Plugin

- Modular Administration Area

* Ajax based Save and Trigger workflow

# SETUP

    1. Download zip file remove the workflow.php file
    2. Upload to server
    3. Configure your pipeline details
    4. Save settings
    5. Trigger workflow
    6. Confirm workflow has been launched from your repo actions

# SETUP WORKFLOW

    1. Navigate to desired repo
    2. Under actions, add workflow pref PHP workflow
    3. Edit the file match the php file attached

# SETUP SECRETS

    1. Under the repo setting, go to developer options
    2. Add new secret
    		FTP_SERVER ---> Server you want to upload to
    		FTP_USERNAME ---> Servers FTP username
    		FTP_PASSWORD  ---> Server FTP password
    		DEV_SERVER ---> Directory you want to upload (Should end with a slash) to eg /apco.jitudevops.com/wp-content/themes/

# IMPROVEMENTS TO BE MADE

    1. Better UI
    2. Update progress function so as to show file transfer progress
