// dashboard.js

// Example function to display a welcome message in the console
function displayWelcomeMessage() {
    console.log("Welcome to the Student Management Dashboard!");
  }
  
 // dashboard.js// Function to toggle the sidebar visibility
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const container = document.querySelector('.container-scroller');
    
    sidebar.classList.toggle('show'); // Show or hide sidebar
    container.classList.toggle('sidebar-show'); // Push content when sidebar is shown
  }
  
  // Event listener for sidebar toggle button
  document.getElementById('sidebarToggle').addEventListener('click', toggleSidebar);
  
  
  // Event listener for when the DOM is loaded
  document.addEventListener('DOMContentLoaded', function() {
    // Display welcome message when the dashboard is loaded
    displayWelcomeMessage();
  
    // Optional: Add event listener for sidebar toggle
    const sidebarToggleButton = document.querySelector('#sidebarToggle');
    if (sidebarToggleButton) {
      sidebarToggleButton.addEventListener('click', toggleSidebar);
    }
  });
  
  // Example of how you could use this script to add dynamic functionality in the future
  // For example: Adding chart data or interactive notifications
  