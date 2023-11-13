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

  // Validate inputs (optional)
  if (!githubUsername || !githubAccessToken || !repositoryName || !repositoryBranch) {
    alert("Please fill in all fields");
    return;
  }

  // Disable the trigger button during the request
  let triggerButton = document.getElementById("trigger-button");
  let saveButton = document.getElementById("save-button");
  triggerButton.disabled = true;
  saveButton.disabled = true;

  // Show loading spinner (optional)
  // document.getElementById("loading-spinner").style.display = "inline";
  // Start fetching and updating progress
  fetchProgress();

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
      console.log(response);
      // Update the displayed credentials
      updateDisplayedCredentials();
      // Re-enable the trigger button
      triggerButton.disabled = false;
      saveButton.disabled = false;
      // Hide loading spinner (optional)
      document.getElementById("loading-spinner").style.display = "none";
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

// Function to fetch and update progress
function fetchProgress() {
  // Function to update progress in the UI
  // Function to update progress in the UI
  function updateProgressUI(response) {
    // Extract the number from the response
    let progressNumber = parseInt(response);

    // Update the value and text content of the progress bar
    let progressBar = document.getElementById("progress-bar");
    progressBar.value = progressNumber;
    progressBar.innerText = progressNumber + "%";
  }

  // Fetch progress from the server
  jQuery.ajax({
    type: "GET",
    url: ajaxurl,
    data: {
      action: "get_download_progress",
    },
    success: function (response) {
      updateProgressUI(response);

      // Check if the download is complete (100%)
      if (response < 100) {
        // If not complete, continue updating the progress
        setTimeout(fetchProgress, 100); // Update every 1 second (adjust as needed)
      } else {
        // Download is complete, display success message or perform any other actions
        document.getElementById("progress-container").innerHTML += "<br>ZIP archive has been extracted successfully.";
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}
