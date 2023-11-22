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
        // $("#response-container").html(response);
        console.log(response);
        console.log("AJAX Request Successful");
      },
      error: function (error) {
        // Handle errors
        // $("#response-container").html(error);
        console.log(error);
        console.log("AJAX Request Error");
      },
    });
  });
});
