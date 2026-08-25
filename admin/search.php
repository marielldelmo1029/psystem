<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check for session validity
if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
    exit;
} else {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Student Management System || Search Students</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">

    <style>
        .table-container {
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: -660px;
            margin-left: 20%;
            display: block;
            width: 100%;
            max-width: 1200px;
        }

        .no-print {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
            }

            body * {
                visibility: hidden;
            }

            .print-area, .print-area * {
                visibility: visible;
            }

            .no-print {
                display: none;
            }

            .print-area {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
                font-size: 12px;
            }

            thead {
                display: table-header-group;
            }

            tbody {
                display: table-row-group;
            }

            /* Hide action buttons in print view */
            .action-buttons {
                display: none;
            }
        }
    </style>
</head>

<body>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/sidebar.php'); ?>
    <div class="table-container">
        <form method="post">
            <div class="form-group">
                <strong>Search Student:</strong>
                <input id="searchdata" type="text" name="searchdata" required="true" class="form-control" placeholder="Search by Student ID, Name, Email, Class-Section, Status, or Age">
            </div>
            <button type="submit" class="btn btn-primary no-print" name="search" id="submit">Search</button>
        </form>

        <?php
        if (isset($_POST['search'])) {
            $sdata = $_POST['searchdata'];
            $ageFilter = false;

            // Check if the search is for age
            if (stripos($sdata, 'age') === 0) {
                $ageParts = explode(' ', $sdata);
                if (isset($ageParts[1]) && is_numeric($ageParts[1])) {
                    $ageFilter = true;
                    $age = (int)$ageParts[1];
                }
            }

            // Base SQL query
            $sql = "SELECT tblstudent.*, tblclass.ClassName, tblclass.Section,
                           TIMESTAMPDIFF(YEAR, tblstudent.DOB, CURDATE()) AS StudentAge
                    FROM tblstudent 
                    LEFT JOIN tblclass ON tblclass.ID = tblstudent.StudentClass";

            if ($ageFilter) {
                // If searching by age
                $sql .= " WHERE TIMESTAMPDIFF(YEAR, tblstudent.DOB, CURDATE()) = :age";
            } else {
                // General search
                $sql .= " WHERE (tblstudent.StuID LIKE :search 
                        OR tblstudent.StudentName LIKE :search 
                        OR tblstudent.StudentEmail LIKE :search 
                        OR CONCAT(tblclass.ClassName, tblclass.Section) LIKE :search
                        OR tblstudent.StudentStatus LIKE :search)";
            }

            $query = $dbh->prepare($sql);

            if ($ageFilter) {
                $query->bindValue(':age', $age, PDO::PARAM_INT);
            } else {
                $query->bindValue(':search', "%$sdata%", PDO::PARAM_STR);
            }

            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) {
                echo "<h4 align='center'>Result against \"$sdata\" keyword</h4>";
        ?>
                <div class="table-responsive border rounded p-1 print-area">
                <h4 class="text-center">Student Information</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Student ID</th>
                                <th>Student Class</th>
                                <th>Student Name</th>
                                <th>Student Email</th>
                                <th>Father Name</th>
                                <th>Mother Name</th>
                                <th>Contact Number</th>
                                <th>Date of Birth</th>
                                <th>Age</th>
                                <th>Student Status</th>
                                <th class="no-print">Actions</th> <!-- Action column header -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            foreach ($results as $row) {
                            ?>
                                <tr>
                                    <td><?php echo htmlentities($cnt); ?></td>
                                    <td><?php echo htmlentities($row->StuID); ?></td>
                                    <td><?php echo htmlentities($row->ClassName); ?> - <?php echo htmlentities($row->Section); ?></td>
                                    <td><?php echo htmlentities($row->StudentName); ?></td>
                                    <td><?php echo htmlentities($row->StudentEmail); ?></td>
                                    <td><?php echo htmlentities($row->FatherName); ?></td>
                                    <td><?php echo htmlentities($row->MotherName); ?></td>
                                    <td><?php echo htmlentities($row->ContactNumber); ?></td>
                                    <td><?php echo htmlentities($row->DOB); ?></td>
                                    <td><?php echo htmlentities($row->StudentAge); ?></td>
                                    <td><?php echo htmlentities($row->StudentStatus); ?></td>
                                    <td class="no-print"> <!-- Actions column -->
                                        <a href="view_student.php?id=<?php echo htmlentities($row->ID); ?>" class="btn btn-sm btn-primary">View</a>
                                        <a href="delete_student.php?id=<?php echo htmlentities($row->ID); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                    </td>
                                </tr>
                            <?php
                                $cnt++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <button class="no-print" onclick="printSearchResults()">Generate Report</button>
        <?php
            } else {
                echo "<p>No records found</p>";
            }
        }
        ?>
    </div>

    <script>
        function printSearchResults() {
            window.print();
        }
    </script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
