<?php
session_start();
include('includes/dbconnection.php');  // Include the PDO connection

// Check if the session is valid
if (!isset($_SESSION['sturecmsstuid']) || strlen($_SESSION['sturecmsstuid']) == 0) {
    echo "Session is not valid. Please log in again.";
    exit();
}

$stuid = $_SESSION['sturecmsstuid'];

// Fetch student data based on session ID (StuID)
$sql = "SELECT * FROM tblstudent WHERE StuID = ?";
$stmt = $dbh->prepare($sql);

if ($stmt->execute([$stuid])) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        // Assign ClassID from the student record to session if not already done
        $_SESSION['ClassID'] = $row['StudentClass'];  // Make sure StudentClass or ClassID is being fetched correctly
    } else {
        echo "No data found for this student ID.<br>";
    }
} else {
    echo "Failed to execute query.<br>";
    exit();
}

// Fetch class announcements based on the student's class
$stuclass = $_SESSION['ClassID']; // Ensure that ClassID is available in the session

$sql_class_announcements = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblnotice.NoticeTitle, tblnotice.CreationDate, tblnotice.ClassId, tblnotice.NoticeMsg, tblnotice.ID as nid 
                            FROM tblnotice 
                            JOIN tblclass ON tblclass.ID = tblnotice.ClassId 
                            WHERE tblnotice.ClassId = :stuclass";

$query_class_announcements = $dbh->prepare($sql_class_announcements);
$query_class_announcements->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
$query_class_announcements->execute();
$results_class_announcements = $query_class_announcements->fetchAll(PDO::FETCH_OBJ);

// Fetch public announcements from tblpublicnotice
$sql_public_announcements = "SELECT NoticeTitle, NoticeMessage, CreationDate, ID as nid FROM tblpublicnotice";
$query_public_announcements = $dbh->prepare($sql_public_announcements);
$query_public_announcements->execute();
$results_public_announcements = $query_public_announcements->fetchAll(PDO::FETCH_OBJ);

// Count records for Class Announcement
$count_class_announcement = count($results_class_announcements);

// Count records for Public Announcement
$count_public_announcement = count($results_public_announcements);

// Count records for Available Forms (using form_fields table)
$sql_available_forms = "SELECT COUNT(*) FROM form_fields";  // Using form_fields table to count available forms
$stmt_available_forms = $dbh->prepare($sql_available_forms);
$stmt_available_forms->execute();
$count_available_forms = $stmt_available_forms->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Management System | Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/sidebar.css">

    <style>
      body{
        background: lightblue;
      }
        .card {
            margin: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        .card-header {
            background-color: #007bff; /* Blue background for the header */
            color: white;
        }
        .card-body {
            padding: 20px;
        }
        .main-panel {
            margin-top: 5%;
            margin-left: auto;
            margin-right: auto;
            display: block;
            width: 100%;
            max-width: 900px;
        }
        .no-notice {
            color: red;
            font-size: 18px;
            margin-top: 20px;
        }
        .view-more-btn {
            margin-top: 20px;
        }
        .header{
background: blue;
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <?php include_once('includes/header.php'); ?>
        <?php include_once('includes/sidebar.php'); ?>

        <div class="main-panel">
            <div class="content-wrapper">
                <h3 class="page-title">Student Dashboard</h3>

                <div class="row">
                    <!-- Public Announcement Card -->
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-header">
                                Public Announcement
                            </div>
                            <div class="card-body">
                                <?php
                                if ($count_public_announcement > 0) {
                                    foreach ($results_public_announcements as $row) {
                                ?>
                                <table border="1" class="table table-bordered mg-b-0">
                                    <tr align="center" class="table-warning">
                                        <td colspan="4" style="font-size:20px;color:blue">Notice</td>
                                    </tr>
                                    <tr class="table-info">
                                        <th>Notice Announced Date</th>
                                        <td><?php echo $row->CreationDate; ?></td>
                                    </tr>
                                    <tr class="table-info">
                                        <th>Notice Title</th>
                                        <td><?php echo $row->NoticeTitle; ?></td>
                                   
                                </table>
                                <?php
                                    }
                                } else {
                                ?>
                                <p class="no-notice">No Public Announcement Found.</p>
                                <?php } ?>
                            </div>
                            <div class="card-footer">
                                <a href="view-public-announcement.php" class="btn btn-secondary view-more-btn">View More</a>
                            </div>
                        </div>
                    </div>

                    <!-- Available Forms Card 
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-header">
                                Available Forms
                            </div>
                            <div class="card-body">
                                <p>Forms: <?php echo $count_available_forms; ?></p>
                            </div>
                            <div class="card-footer">
                                <a href="available-forms.php" class="btn btn-secondary view-more-btn">View More</a>
                            </div>
                        </div>
                    </div>-->

                    <!-- Update Info Card 
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-header">
                                Update Info
                            </div>
                            <div class="card-body">
                                <br>
                                <a href="update-info.php" class="btn btn-secondary view-more-btn">Update Information</a>
                            </div>
                        </div>
                    </div>
                    -->

                    <!-- Class Announcements -->
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-header">
                                Class Announcements
                            </div>
                            <div class="card-body">
                                <?php
                                if ($count_class_announcement > 0) {
                                    foreach ($results_class_announcements as $row) {
                                ?>
                                <table border="1" class="table table-bordered mg-b-0">
                                    <tr align="center" class="table-warning">
                                        <td colspan="4" style="font-size:20px;color:blue">Notice</td>
                                    </tr>
                                    <tr class="table-info">
                                        <th>Notice Announced Date</th>
                                        <td><?php echo $row->CreationDate; ?></td>
                                    </tr>
                                    <tr class="table-info">
                                        <th>Notice Title</th>
                                        <td><?php echo $row->NoticeTitle; ?></td>
                                    </tr>
                                   
                                </table>
                                <?php
                                    }
                                } else {
                                ?>
                                <p class="no-notice">No Class Announcement Found.</p>
                                <?php } ?>
                            </div>
                            <div class="card-footer">
                                <a href="view-class-announcement.php" class="btn btn-secondary view-more-btn">View More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
