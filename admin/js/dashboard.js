// Custom JavaScript for Dashboard Interactions

// Chart Update Logic (Optional, could be based on real-time data fetching)
function updateStudentEnrollmentChart() {
    // Example of fetching data from server to update chart
    fetch('path_to_your_api_or_backend_script.php')
      .then(response => response.json())
      .then(data => {
        // Assuming the API returns data in the form of an array of numbers for class enrollments
        var updatedData = {
          labels: ['Class 1', 'Class 2', 'Class 3', 'Class 4'],
          datasets: [{
            label: 'Number of Students',
            data: data.enrollments,  // Example data
            backgroundColor: ['rgba(75, 192, 192, 0.2)'],
            borderColor: ['rgba(75, 192, 192, 1)'],
            borderWidth: 1
          }]
        };
  
        // Update chart with new data
        studentEnrollmentChart.data = updatedData;
        studentEnrollmentChart.update();
      })
      .catch(error => console.error('Error fetching data:', error));
  }
  
  // Daterange Picker Setup for Filtering (If needed)
  $(function() {
    $('#reportrange').daterangepicker({
      startDate: moment().subtract(29, 'days'),
      endDate: moment(),
      minDate: '01/01/2020',
      maxDate: moment(),
      dateLimit: { days: 60 },
      showDropdowns: true,
      showWeekNumbers: true,
      timePicker: false,
      opens: 'left',
      drops: 'down',
      buttonClasses: 'btn btn-sm',
      applyClass: 'btn-primary',
      cancelClass: 'btn-secondary',
      format: 'MM/DD/YYYY',
      separator: ' to ',
      locale: {
        applyLabel: 'Apply',
        cancelLabel: 'Cancel',
        fromLabel: 'From',
        toLabel: 'To',
        customRangeLabel: 'Custom Range'
      }
    }, function(start, end, label) {
      console.log("Selected range: " + start.format('MM/DD/YYYY') + ' to ' + end.format('MM/DD/YYYY'));
      // Add logic to update the data based on selected range, if necessary
    });
  });
  
  // Handling the Refresh Button Click (Manual Data Refresh)
  document.querySelector('.btn-refresh').addEventListener('click', function() {
    // This could trigger a function to refresh data in sections of the dashboard
    updateDashboardData();  // A function to fetch and update data
  });
  
  // Example function to refresh various data points (e.g., total classes, students, etc.)
  function updateDashboardData() {
    // Fetch new data and update dashboard sections (Total Classes, Students, Notices, etc.)
    fetch('path_to_your_data_endpoint.php')  // Replace with your API or backend endpoint
      .then(response => response.json())
      .then(data => {
        // Update elements with fetched data
        document.querySelector('#totalClasses').textContent = data.totalClasses;
        document.querySelector('#totalStudents').textContent = data.totalStudents;
        document.querySelector('#totalNotices').textContent = data.totalNotices;
      })
      .catch(error => console.error('Error fetching data:', error));
  }
  
  // Trigger initial data load
  updateDashboardData();  // Initial data fetch when the page loads
  updateStudentEnrollmentChart();  // Initial chart load
  
  // Optional: Any additional functionality for dynamic updates, like hiding/showing elements, etc.
  document.querySelector('#toggleSidebar').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('active');
  });
  
  // Optionally, you can listen for window resizing if needed (e.g., for responsiveness)
  window.addEventListener('resize', function() {
    // Handle chart or layout adjustments based on window size
    studentEnrollmentChart.resize();
  });
  
  // Handle form submissions for searching or filtering
  document.querySelector('#searchForm').addEventListener('submit', function(event) {
    event.preventDefault();  // Prevent default form submission
    const searchQuery = document.querySelector('#searchInput').value;
    // Do something with searchQuery, like filtering data
  });
  