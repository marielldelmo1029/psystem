<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
    // Handle form field creation
    if (isset($_POST['submit_field'])) {
        $label = $_POST['label'];
        $field_name = $_POST['field_name'];
        $field_type = $_POST['field_type'];

        // Adjust the value of is_required based on checkbox state
        $is_required = isset($_POST['is_required']) ? 'Yes' : 'No'; // Change to 'Yes'/'No' instead of 1/0

        try {
            // Insert new field into form_fields table
            $sql = "INSERT INTO form_fields (label, field_name, field_type, required) 
                    VALUES (:label, :field_name, :field_type, :is_required)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':label', $label, PDO::PARAM_STR);
            $query->bindParam(':field_name', $field_name, PDO::PARAM_STR);
            $query->bindParam(':field_type', $field_type, PDO::PARAM_STR);
            $query->bindParam(':is_required', $is_required, PDO::PARAM_STR); // Bind as string
            $query->execute();

            echo '<script>alert("Field has been added successfully!");</script>';
            echo '<script>window.location.href="create_student_form.php";</script>';
        } catch (Exception $e) {
            echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
        }
    }

    // Handle field deletion
    if (isset($_GET['delete_field_id'])) {
        $field_id = intval($_GET['delete_field_id']);
        
        // Delete all responses that use the field being deleted
        $sql_responses = "DELETE FROM student_form_responses WHERE field_id = :field_id";
        $query_responses = $dbh->prepare($sql_responses);
        $query_responses->bindParam(':field_id', $field_id, PDO::PARAM_INT);
        $query_responses->execute();
        
        // Now delete the field itself from form_fields
        $sql = "DELETE FROM form_fields WHERE id = :field_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':field_id', $field_id, PDO::PARAM_INT);
        $query->execute();

        echo '<script>alert("Field has been deleted successfully.");</script>';
        echo '<script>window.location.href="create_student_form.php";</script>';
    }

    // Handle creating a new form (deleting all fields and responses)
    if (isset($_POST['create_new_form'])) {
        echo '<script>
            var confirmDelete = confirm("You are about to delete all form fields and responses. Are you sure you want to proceed?");
            if (confirmDelete) {
                var finalConfirm = confirm("This action will delete all form fields and responses permanently. Are you sure you want to continue?");
                if (finalConfirm) {
                    window.location.href = "create_student_form.php?confirm_delete=1";
                }
            }
        </script>';
    }

    // Handle final deletion if confirmed
    if (isset($_GET['confirm_delete']) && $_GET['confirm_delete'] == 1) {
        try {
            $dbh->beginTransaction();

            // Delete all responses
            $sql_responses = "DELETE FROM student_form_responses";
            $query_responses = $dbh->prepare($sql_responses);
            $query_responses->execute();

            // Delete all form fields
            $sql_fields = "DELETE FROM form_fields";
            $query_fields = $dbh->prepare($sql_fields);
            $query_fields->execute();

            $dbh->commit();

            echo '<script>alert("All form fields and responses have been cleared. You can now create a new form.");</script>';
            echo '<script>window.location.href="create_student_form.php";</script>';
        } catch (PDOException $e) {
            $dbh->rollBack();
            echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Management System | Create Form</title>
    <link rel="stylesheet" href="css/dashboard.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
<style>
    .main-panel {
        margin-top: -660px;
        margin-left: auto;
        margin-right: auto;
        display: block;
        width: 100%;
        max-width: 900px;
    }

    .dropdown-menu {
        background-color: #f3f7fc;
        border: 1px solid #d6e0f5;
        margin-left: -100px;
    }
</style>
</head>
<body>
    <div class="container-fluid">
        <?php include_once('includes/header.php'); ?>
    </div>
    <?php include_once('includes/sidebar.php'); ?>

    <!-- Section for Viewing Student Details -->
     
    <div class="container">
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" style="text-align: center;">View Student Details</h4>
                                <form class="forms-sample">
                                    <div class="form-group">
                                        <label for="exampleInputName1">Student Name</label>
                                        <input type="text" name="stuname" value="" class="form-control" readonly="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">Student ID</label>
                                        <input type="text" name="stuid" value="" class="form-control" readonly="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">Student Email</label>
                                        <input type="text" name="stuemail" value="" class="form-control" readonly="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Student Class</label>
                                        <select name="stuclass" class="form-control" disabled="true">
                                            <option value="">Select Class</option>
                                            <!-- Insert your options dynamically here -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">Gender</label>
                                        <select name="gender" class="form-control">
                                            <option value="Female" disabled selected>Female</option>
                                            <option value="Male" disabled selected>Male</option>
                                            <option value="" disabled selected>Choose Gender</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">Date of Birth</label>
                                        <input type="date" name="dob" value="" class="form-control" readonly="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">Father's Name</label>
                                        <input type="text" name="father_name" value="" class="form-control" readonly="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">Mother's Name</label>
                                        <input type="text" name="mother_name" value="" class="form-control" readonly="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">Contact Number</label>
                                        <input type="text" name="contact" value="" class="form-control" readonly="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">Alternate Contact Number</label>
                                        <input type="text" name="alt_contact" value="" class="form-control" readonly="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">Address</label>
                                        <input type="text" name="address" value="" class="form-control" readonly="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">Student Status</label>
                                        <input type="text" name="status" value="" class="form-control" readonly="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">Student Photo</label>
                                        <input type="file" name="image" value="" class="form-control" disabled="true">
                                    </div>

                                    <?php
                                    // Fetch additional fields dynamically from the form_fields table
                                    $sql = "SELECT * FROM form_fields";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $fields = $query->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($fields as $field) {
                                        echo "<div class='form-group'>";
                                        echo "<label for='" . htmlentities($field->field_name) . "'>" . htmlentities($field->label) . "</label>";
                                        echo "<input type='" . htmlentities($field->field_type) . "' name='" . htmlentities($field->field_name) . "' value='' class='form-control' readonly='true'>";
                                        echo "</div>";
                                    }
                                    ?>
                                  
                                   <!-- <button type="submit" class="btn btn-primary mr-2" disabled>View</button> -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section for Managing Form Fields (Editable) -->
                <div class="form-management">
                    <h3 class="card-title">Create Form</h3>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="label">Field Label</label>
                            <input type="text" class="form-control" name="label" id="label" placeholder="e.g. Full Name" required />
                        </div>
                        <div class="form-group">
                            <label for="field_name">Field Name</label>
                            <input type="text" class="form-control" name="field_name" id="field_name" placeholder="e.g. full_name" required />
                        </div>
                        <div class="form-group">
                            <label for="field_type">Field Type</label>
                            <select name="field_type" id="field_type" class="form-control" required>
                                <option value="text" selected>Text</option>
                                <option value="email">Email</option>
                                <option value="date">Date</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" name="is_required" id="is_required" />
                            <label for="is_required">Required Field</label>
                        </div>
                        <button type="submit" name="submit_field" class="btn btn-primary">Add Field</button>
                    </form>

                    <h3 class="card-title mt-4">Existing Fields</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Label</th>
                                <th>Field Name</th>
                                <th>Field Type</th>
                                <th>Required</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <?php
                            $sql = "SELECT * FROM form_fields";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $fields = $query->fetchAll(PDO::FETCH_OBJ);
                            foreach ($fields as $field) {
                                echo "<tr>";
                                echo "<td>" . htmlentities($field->label) . "</td>";
                                echo "<td>" . htmlentities($field->field_name) . "</td>";
                                echo "<td>" . htmlentities($field->field_type) . "</td>";
                                echo "<td>" . ($field->required == 'Yes' ? "Yes" : "No") . "</td>";
                                echo "<td>";
                                echo "<a href='create_student_form.php?delete_field_id=" . $field->id . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this field?\")'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
<!--
                    <form method="post" action="">
                        <button type="submit" name="create_new_form" class="btn btn-secondary mt-3">Create New Form</button> -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body>
<script src="js/bootstrap.bundle.min.js"></script>
</html>
