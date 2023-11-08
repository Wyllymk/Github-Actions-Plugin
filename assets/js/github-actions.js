function saveSettings() {
  let githubUsername = document.getElementById("github-username").value;
  let githubRepository = document.getElementById("github-repository").value;
  let githubAccessToken = document.getElementById("github-access-token").value;
  let githubEventType = document.getElementById("github-event-type").value;

  jQuery.post(
    ajaxurl,
    {
      action: "save_settings",
      github_username: githubUsername,
      github_repository: githubRepository,
      github_access_token: githubAccessToken,
      github_event_type: githubEventType,
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
  let githubRepository = document.getElementById("github-repository").value;
  let githubAccessToken = document.getElementById("github-access-token").value;
  let githubEventType = document.getElementById("github-event-type").value;

  jQuery.post(
    ajaxurl,
    {
      action: "trigger_workflow",
      github_username: githubUsername,
      github_repository: githubRepository,
      github_access_token: githubAccessToken,
      github_event_type: githubEventType,
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
  let githubRepository = document.getElementById("github-repository").value;
  let githubAccessToken = document.getElementById("github-access-token").value;
  let githubEventType = document.getElementById("github-event-type").value;

  document.getElementById("display-credentials").innerHTML = `
                    <h3>Entered Credentials</h3>
                    <p><strong>GitHub Username:</strong> ${githubUsername}</p>
                    <p><strong>GitHub Repository:</strong> ${githubRepository}</p>
                    <p><strong>GitHub Access Token:</strong> ${githubAccessToken}</p>
                    <p><strong>Event Type:</strong> ${githubEventType}</p>
                `;
}
