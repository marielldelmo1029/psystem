<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Student Management System | View Student Details</title>
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

        input[readonly] {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }

        select[disabled] {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <?php include_once('includes/header.php'); ?>
    </div>
    <?php include_once('includes/sidebar.php'); ?>
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
                                    <label for="exampleInputName1">Student ID</label>
                                    <input type="text" name="stuid" value="" class="form-control" readonly="true">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName1">Student Photo</label>
                                    <input type="file" name="image" value="" class="form-control" disabled="true">
                                </div>

                              
                                <button type="submit" class="btn btn-primary mr-2" disabled>View</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
</body>

<script src="js/bootstrap.bundle.min.js"></script>

</html>
<?php } ?>
