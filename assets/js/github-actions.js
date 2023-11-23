window.addEventListener("load", function () {
  // Store Tabs variables
  const tabs = document.querySelectorAll("ul.nav-tabs > li");

  // Define the switchTab function
  const switchTab = (event) => {
    event.preventDefault();

    document.querySelector("ul.nav-tabs li.active").classList.remove("active");
    document.querySelector(".tab-pane.active").classList.remove("active");

    const clickedTab = event.currentTarget;
    const anchor = event.target;
    let activePaneID = anchor.getAttribute("href");

    clickedTab.classList.add("active");
    document.querySelector(activePaneID).classList.add("active");
  };

  // Attach the event listener to each tab
  for (let i = 0; i < tabs.length; i++) {
    tabs[i].addEventListener("click", switchTab);
  }
});

// Trigger workflow button
jQuery(document).ready(function ($) {
  $("#trigger-workflow-button").on("click", function (e) {
    e.preventDefault();

    // Disable the trigger button
    $(this).prop("disabled", true);

    // Show the loading spinner
    $("#loading-spinner").css("display", "block");

    // Perform the AJAX request
    $.ajax({
      type: "POST",
      url: workflowAjax.ajaxurl, // WordPress AJAX endpoint
      data: {
        action: "trigger_workflow_action",
        nonce: workflowAjax.nonce,
      },
      success: function (response) {
        // Handle the response from the server
        $("#response-success").html(response);
      },
      error: function (error) {
        // Handle errors
        $("#response-error").html(error);
      },
      complete: function () {
        // Enable the trigger button after the request is complete (success or error)
        $("#trigger-workflow-button").prop("disabled", false);

        // Hide the loading spinner
        $("#loading-spinner").css("display", "none");
      },
    });
  });
});
