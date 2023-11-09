function saveSettings() {
  let githubUsername = document.getElementById("github-username").value;
  let githubAccessToken = document.getElementById("github-access-token").value;
  let repositoryName = document.getElementById("repository-name").value;
  let repositoryBranch = document.getElementById("repository-branch").value;

  jQuery.post(
    ajaxurl,
    {
      action: "save_settings",
      github_username: githubUsername,
      github_access_token: githubAccessToken,
      repository_name: repositoryName,
      repository_branch: repositoryBranch,
    },
    function (response) {
      document.getElementById("response").innerHTML = response;
      // Update the displayed credentials
      updateDisplayedCredentials();
    }
  );
}

function triggerWorkflow() {
  let githubUsername = document.getElementById("github-username").value;
  let githubAccessToken = document.getElementById("github-access-token").value;
  let repositoryName = document.getElementById("repository-name").value;
  let repositoryBranch = document.getElementById("repository-branch").value;

  jQuery.post(
    ajaxurl,
    {
      action: "trigger_workflow",
      github_username: githubUsername,
      github_access_token: githubAccessToken,
      repository_name: repositoryName,
      repository_branch: repositoryBranch,
    },
    function (response) {
      document.getElementById("response").innerHTML = response;
      // Update the displayed credentials
      updateDisplayedCredentials();
    }
  );
}

function updateDisplayedCredentials() {
  let githubUsername = document.getElementById("github-username").value;
  let githubAccessToken = document.getElementById("github-access-token").value;
  let repositoryName = document.getElementById("repository-name").value;
  let repositoryBranch = document.getElementById("repository-branch").value;

  document.getElementById("github-javascript-username").innerHTML = `${githubUsername}`;
  document.getElementById("github-javascript-token").innerHTML = `${githubAccessToken}`;
  document.getElementById("github-javascript-name").innerHTML = `${repositoryName}`;
  document.getElementById("github-javascript-branch").innerHTML = `${repositoryBranch}`;
}
