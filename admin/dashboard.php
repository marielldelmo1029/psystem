<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {

    // Approve student
    if (isset($_GET['approve'])) {
        $student_id = $_GET['approve'];
        try {
            $sql = "UPDATE tblstudent SET status = 'active' WHERE StuID = ?";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$student_id]);
            echo "<script>alert('Student approved successfully.');</script>";
            echo "<script>window.location.href='dashboard.php';</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }

    // Disapprove student (Delete from the database)
    if (isset($_GET['disapprove'])) {
        $student_id = $_GET['disapprove'];
        try {
            // Query to delete the student's record from the database
            $sql = "DELETE FROM tblstudent WHERE StuID = ?";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$student_id]);
            echo "<script>alert('Student deleted successfully.');</script>";
            echo "<script>window.location.href='dashboard.php';</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System | Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <div class="container-fluid">
        <?php include_once('includes/header.php'); ?>
        <div class="row">
            <!-- Sidebar -->
            <?php include_once('includes/sidebar.php'); ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <h2 class="text-center">Dashboard</h2>

                <!-- Cards Section -->
                <div class="row">
                    <!-- Total Classes Card -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                $sql1 = "SELECT * FROM tblclass";
                                $query1 = $dbh->prepare($sql1);
                                $query1->execute();
                                $totclass = $query1->rowCount();
                                ?>
                                <h5>Total Classes</h5>
                                <h4><?php echo htmlentities($totclass); ?></h4>
                                <a href="manage_class.php" class="btn btn-light btn-sm">View Classes</a>
                            </div>
                        </div>
                    </div>

                    <!-- Total Students Card -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                // Count only approved students (status = 'active')
                                $sql2 = "SELECT * FROM tblstudent WHERE status = 'active'";
                                $query2 = $dbh->prepare($sql2);
                                $query2->execute();
                                $totstu = $query2->rowCount();
                                ?>
                                <h5>Total Students</h5>
                                <h4><?php echo htmlentities($totstu); ?></h4>
                                <a href="manage_students.php" class="btn btn-light btn-sm">View Students</a>
                            </div>
                        </div>
                    </div>

                    <!-- Total Notices Card -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                $sql3 = "SELECT * FROM tblnotice";
                                $query3 = $dbh->prepare($sql3);
                                $query3->execute();
                                $totnotice = $query3->rowCount();
                                ?>
                                <h5>Total Class Announcements</h5>
                                <h4><?php echo htmlentities($totnotice); ?></h4>
                                <a href="manage_notice.php" class="btn btn-light btn-sm">View Announcements</a>
                            </div>
                        </div>
                    </div>

                    <!-- Total Public Notices Card -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                $sql4 = "SELECT * FROM tblpublicnotice";
                                $query4 = $dbh->prepare($sql4);
                                $query4->execute();
                                $totpublicnotice = $query4->rowCount();
                                ?>
                                <h5>Total Public Notice</h5>
                                <h4><?php echo htmlentities($totpublicnotice); ?></h4>
                                <a href="manage_public_notice.php" class="btn btn-light btn-sm">View Public Announcements</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirm Students Section -->
                <br><br>
                <h4>Pending Student Registrations</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Class</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $sql = "SELECT tblstudent.StuID, tblstudent.StudentName, tblstudent.StudentEmail, 
                                           tblstudent.ContactNumber, tblclass.ClassName, tblclass.Section 
                                    FROM tblstudent
                                    LEFT JOIN tblclass ON tblclass.ID = tblstudent.StudentClass
                                    WHERE tblstudent.status = 'pending'
                                    ORDER BY tblstudent.StuID";
                            $stmt = $dbh->prepare($sql);
                            $stmt->execute();
                            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if ($stmt->rowCount() > 0) {
                                foreach ($students as $student) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($student['StuID']) . "</td>";
                                    echo "<td>" . htmlspecialchars($student['StudentName']) . "</td>";
                                    echo "<td>" . htmlspecialchars($student['StudentEmail']) . "</td>";
                                    echo "<td>" . htmlspecialchars($student['ContactNumber']) . "</td>";
                                    echo "<td>" . htmlspecialchars($student['ClassName']) . " " . htmlspecialchars($student['Section']) . "</td>";
                                    echo "<td>
                                            <a href='dashboard.php?approve=" . $student['StuID'] . "' class='btn btn-success btn-sm'>Approve</a>
                                            <a href='dashboard.php?disapprove=" . $student['StuID'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Disapprove and Delete</a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No pending student registrations found.</td></tr>";
                            }
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='6'>Error: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

  
</body>
<script src="js/bootstrap.bundle.min.js"></script>

</html>
<?php } ?>
