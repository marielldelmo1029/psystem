<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    // Code for deletion
    if (isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);
        $sql = "DELETE FROM tblstudent WHERE ID=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Data deleted');</script>";
        echo "<script>window.location.href = 'graduate_students.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System | Graduate Students</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        body {
            overflow: hidden;
        }

        /* Vibrant Blue Print Button */
        .btn {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .btn-print:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-print {
            padding: 10px 20px;
        }

        .card {
            margin-top: -700px;
            margin-left: auto;
            margin-right: auto;
            display: block;
            width: 100%;
            max-width: 1500px;
        }

        /* Styling for the print view */
        @media print {
            .main-panel,
            .sidebar,
            .footer,
            .action-column,
            .btn,
            .pagination {
                display: none;
            }

            .table {
                width: 100%;
                border: 1px solid #ddd;
                text-align: center;
            }

            .table th,
            .table td {
                padding: 8px;
                text-align: center;
                vertical-align: middle;
            }

            h2 {
                text-align: center;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <?php include_once('includes/header.php'); ?>
    </div>
    <?php include_once('includes/sidebar.php'); ?>

    <div class="main-panel flex-grow-1">
        <div class="content-wrapper mx-auto p-4">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-sm-flex align-items-center mb-4">
                                <h4 class="card-title mb-sm-0">Graduate Students</h4>
                                <!-- Print Button -->
                                <button class="btn btn-print ml-auto" onclick="printPage()">Print</button>
                            </div>

                            <div class="table-responsive border rounded p-1">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Student ID</th>
                                            <th>Student Class</th>
                                            <th>Student Name</th>
                                            <th>Student Email</th>
                                            <th>Admission Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Current page number
                                        if (isset($_GET['pageno'])) {
                                            $pageno = $_GET['pageno'];
                                        } else {
                                            $pageno = 1;
                                        }

                                        $no_of_records_per_page = 10;
                                        $offset = ($pageno - 1) * $no_of_records_per_page;

                                        // Get total rows for pagination
                                        $ret = "SELECT COUNT(*) as total FROM tblstudent WHERE StudentStatus = 'graduate'";
                                        $query1 = $dbh->prepare($ret);
                                        $query1->execute();
                                        $row_count = $query1->fetch(PDO::FETCH_ASSOC);
                                        $total_rows = $row_count['total'];
                                        $total_pages = ceil($total_rows / $no_of_records_per_page);

                                        // Fetch data for the current page
                                        $sql = "SELECT tblstudent.StuID, tblstudent.ID as sid, tblstudent.StudentName, 
                                                    tblstudent.StudentEmail, tblstudent.DateofAdmission, 
                                                    tblclass.ClassName, tblclass.Section 
                                                FROM tblstudent 
                                                JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                                                WHERE tblstudent.StudentStatus = 'graduate' 
                                                LIMIT $offset, $no_of_records_per_page";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        $row_number = $offset + 1; // Row number starts from offset + 1
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) { ?>
                                                <tr>
                                                    <td><?php echo htmlentities($row_number); ?></td>
                                                    <td><?php echo htmlentities($row->StuID); ?></td>
                                                    <td><?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?></td>
                                                    <td><?php echo htmlentities($row->StudentName); ?></td>
                                                    <td><?php echo htmlentities($row->StudentEmail); ?></td>
                                                    <td><?php echo htmlentities($row->DateofAdmission); ?></td>
                                                    <td>
                                                        <a href="view_student.php?id=<?php echo htmlentities($row->sid); ?>" class="btn btn-info btn-sm">View</a>
                                                        <a href="graduate_students.php?delid=<?php echo ($row->sid); ?>"
                                                           onclick="return confirm('Do you really want to Delete ?');"
                                                           class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                        <?php
                                                $row_number++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <div align="center">
                                <div class="btn-group">
                                    <a href="?pageno=1" class="btn"><strong>First</strong></a>
                                    <a href="<?php if ($pageno <= 1) { echo '#'; } else { echo "?pageno=" . ($pageno - 1); } ?>" 
                                       class="btn <?php if ($pageno <= 1) { echo 'disabled'; } ?>"><strong>Prev</strong></a>
                                    <a href="<?php if ($pageno >= $total_pages) { echo '#'; } else { echo "?pageno=" . ($pageno + 1); } ?>" 
                                       class="btn <?php if ($pageno >= $total_pages) { echo 'disabled'; } ?>"><strong>Next</strong></a>
                                    <a href="?pageno=<?php echo $total_pages; ?>" class="btn"><strong>Last</strong></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        function printPage() {
            // Open a new window for printing
            var printWindow = window.open('', '', 'height=800,width=1200');
            printWindow.document.write('<html><head><title>Graduate Students</title>');
            printWindow.document.write('<link rel="stylesheet" href="css/bootstrap.min.css">');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h2>Graduate Students</h2>');
            printWindow.document.write('<table class="table table-bordered">');
            printWindow.document.write('<thead><tr><th>No</th><th>Student ID</th><th>Student Class</th><th>Student Name</th><th>Student Email</th><th>Admission Date</th></tr></thead>');
            printWindow.document.write('<tbody>');
            
            <?php
            // Fetch all data for printing (no pagination)
            $sql_all_print = "SELECT tblstudent.StuID, tblstudent.StudentName, tblstudent.StudentEmail, tblstudent.DateofAdmission, 
                              tblclass.ClassName, tblclass.Section 
                           FROM tblstudent 
                           JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                           WHERE tblstudent.StudentStatus = 'graduate'";

            $query_all_print = $dbh->prepare($sql_all_print);
            $query_all_print->execute();
            $results_all_print = $query_all_print->fetchAll(PDO::FETCH_OBJ);

            $row_number = 1;
            foreach ($results_all_print as $row) { ?>
                printWindow.document.write('<tr>');
                printWindow.document.write('<td><?php echo $row_number++; ?></td>');
                printWindow.document.write('<td><?php echo htmlentities($row->StuID); ?></td>');
                printWindow.document.write('<td><?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?></td>');
                printWindow.document.write('<td><?php echo htmlentities($row->StudentName); ?></td>');
                printWindow.document.write('<td><?php echo htmlentities($row->StudentEmail); ?></td>');
                printWindow.document.write('<td><?php echo htmlentities($row->DateofAdmission); ?></td>');
                printWindow.document.write('</tr>');
            <?php } ?>

            printWindow.document.write('</tbody></table>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</body>

</html>
<?php } ?>
