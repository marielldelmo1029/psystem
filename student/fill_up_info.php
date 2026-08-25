<?php
session_start();
include('includes/dbconnection.php');  // Include the PDO connection

// Check if the session is valid
if (!isset($_SESSION['sturecmsstuid']) || strlen($_SESSION['sturecmsstuid']) == 0) {
    echo "Session is not valid. Please log in again.";
    exit();
}

$stuid = $_SESSION['sturecmsstuid']; // Get the student ID (StuID) from the session

// Fetch the correct ID from tblstudent using StuID
$sql_check = "SELECT ID FROM tblstudent WHERE StuID = :stuid";  // Match StuID with the session variable
$query_check = $dbh->prepare($sql_check);
$query_check->bindParam(':stuid', $stuid, PDO::PARAM_STR);
$query_check->execute();
$student = $query_check->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "Student ID does not exist in the database.";
    exit();
}

$student_id = $student['ID']; // Get the correct ID for insertion

// Fetch the form fields from the database (Admin's dynamic form)
$sql = "SELECT * FROM form_fields ORDER BY id ASC";  // Assuming the admin form fields are in 'form_fields'
$query = $dbh->prepare($sql);
$query->execute();
$form_fields = $query->fetchAll(PDO::FETCH_OBJ);

// Handle form submission (save student responses)
if (isset($_POST['submit_form'])) {
    foreach ($form_fields as $field) {
        // Handle missing 'required' property
        $field_required = $field->required ?? 'No'; // Default to 'No' if 'required' is missing

        if (isset($_POST[$field->field_name]) && !empty($_POST[$field->field_name])) {
            $response = $_POST[$field->field_name];
        } else {
            // If the field is required but not provided, alert the user
            if ($field_required == 'Yes') {
                echo "<script>alert('Please fill in the required field: " . htmlentities($field->label) . "');</script>";
                exit();
            }
            // If the field is not required, use an empty string
            $response = '';
        }

        // Insert student response into the student_form_responses table
        try {
            $sql_insert = "INSERT INTO student_form_responses (student_id, field_id, response) 
                           VALUES (:student_id, :field_id, :response)";
            $query_insert = $dbh->prepare($sql_insert);
            $query_insert->bindParam(':student_id', $student_id, PDO::PARAM_INT);  // Use the correct student ID
            $query_insert->bindParam(':field_id', $field->id, PDO::PARAM_INT);
            $query_insert->bindParam(':response', $response, PDO::PARAM_STR);
            $query_insert->execute();
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
    echo "<script>alert('Your responses have been submitted successfully.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Fill Out Form | Student Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
  <style>
      .page-content {
    margin-top: 50px; /* Adjust margin-top if needed */
    margin-left: auto;
    margin-right: auto;
    display: block;
    width: 100%;
    max-width: 900px;
      }
      body{
    background: lightblue;
  }
  .sidebar {
    position: fixed;
    margin-top: -46px;
    left: 0;
    width: 250px;
    height: 250%;
    background: #007bff; /* Vibrant blue color */
    padding-top: 20px;
    z-index: 1000;
    box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
    border-right: 2px solid #5a8fff;
}
  </style>
  
</head>
<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
     <?php include_once('includes/header.php');?>
      <!-- partial -->
        <!-- partial:partials/_sidebar.html -->
      <?php include_once('includes/sidebar.php');?>
        <!-- Main Content -->
        <div class="page-content">
            <h4>Fill Out the Form</h4>
            
            <?php
            if (empty($form_fields)) {
                // If there are no form fields, display a message
                echo '<div class="alert alert-info">No form has been created as of now. Please check back later.</div>';
            } else {
                // If there are form fields, display the form
                echo '<form method="post" enctype="multipart/form-data">';
                foreach ($form_fields as $field) {
                    echo '<div class="form-group">';
                    echo '<label>' . htmlentities($field->label) . '</label>';
                    
                    // Display different form types (text, textarea, select, etc.)
                    if ($field->field_type == 'text') {
                        echo '<input type="text" name="' . htmlentities($field->field_name) . '" class="form-control">';
                    } elseif ($field->field_type == 'textarea') {
                        echo '<textarea name="' . htmlentities($field->field_name) . '" class="form-control"></textarea>';
                    } elseif ($field->field_type == 'select') {
                        $options = explode(',', $field->options); // Assume options are comma-separated
                        echo '<select name="' . htmlentities($field->field_name) . '" class="form-control">';
                        foreach ($options as $option) {
                            echo '<option value="' . htmlentities($option) . '">' . htmlentities($option) . '</option>';
                        }
                        echo '</select>';
                    } else {
                        echo '<input type="' . htmlentities($field->field_type) . '" name="' . htmlentities($field->field_name) . '" class="form-control">';
                    }
                    echo '</div>';
                }
                echo '<button type="submit" name="submit_form" class="btn btn-primary">Submit</button>';
                echo '</form>';
            }
            ?>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
