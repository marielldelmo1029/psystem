<?php
session_start();
include('includes/dbconnection.php');

// Redirect if session is invalid
if (strlen($_SESSION['sturecmsstuid']) == 0) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System | View Class Notice</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-panel {
            margin: 5% auto;
            max-width: 900px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .page-header h3 {
            margin-bottom: 5px;
        }

        .page-header p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }
        
/* Individual Card Styling */
.card {
    background-color: white;
    color: #333;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
    margin-bottom: 20px;
    width: calc(50% - 10px); /* Adjust the width to fit two cards in a row */
}

.card:hover {
    transform: scale(1.05); /* Slight scaling effect on hover */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Stronger shadow on hover */
}

.card .card-body {
    background-color: transparent;
    padding: 1.5rem;
}

.card-header {
    background-color: #007bff; /* Match sidebar color */
    color: white;
    font-weight: bold;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .sidebar {
        width: 200px; /* Reduce sidebar width on small screens */
    }

    .page-content {
        margin-left: 200px; /* Adjust content area to fit smaller sidebar */
    }

    .card {
        width: 100%; /* Stack cards vertically on smaller screens */
    }
}
        body{
    background: lightblue;
  }

  .card{
	margin-top:5%;
 margin-left: auto;
    margin-right: auto;
    display: block;
    width: 100%;
    max-width: 800px;
}
        .table {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .table th, .table td {
            text-align: left;
            padding: 12px;
        }

        .table th {
            background-color: #f1f3f5;
            font-weight: bold;
        }

        .no-notice {
            text-align: center;
            color: #ff0000;
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .btn-back:hover {
            background-color: #0056b3;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container-scroller">
    <?php include_once('includes/header.php'); ?>
    <?php include_once('includes/sidebar.php'); ?>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3>Class Announcement</h3>
                <p>Stay updated with your class-specific announcements</p>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <?php
                                $stuclass = $_SESSION['stuclass'];
                                $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblnotice.NoticeTitle, tblnotice.CreationDate, tblnotice.ClassId, tblnotice.NoticeMsg, tblnotice.ID as nid 
                                        FROM tblnotice 
                                        JOIN tblclass ON tblclass.ID = tblnotice.ClassId 
                                        WHERE tblnotice.ClassId = :stuclass";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) {
                                ?>
                                <thead>
                                <tr>
                                    <th colspan="2">Notice Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>Notice Announced Date</th>
                                    <td><?php echo htmlspecialchars($row->CreationDate); ?></td>
                                </tr>
                                <tr>
                                    <th>Notice Title</th>
                                    <td><?php echo htmlspecialchars($row->NoticeTitle); ?></td>
                                </tr>
                                <tr>
                                    <th>Message</th>
                                    <td><?php echo nl2br(htmlspecialchars($row->NoticeMsg)); ?></td>
                                </tr>
                                </tbody>
                                <?php
                                    }
                                } else {
                                ?>
                                <tr>
                                    <td colspan="2" class="no-notice">No Class Announcements Found</td>
                                </tr>
                                <?php } ?>
                            </table>
                            <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
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
<?php } ?>
