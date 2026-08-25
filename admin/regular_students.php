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
        echo "<script>window.location.href = 'regular_students.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System | Regular Students</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
      body {
        overflow: hidden;
      }
     /* Vibrant Blue Print Button */
    .btn{
        background-color: #007bff; /* Vibrant Blue */
        border-color: #007bff;     /* Matching border color */
        color: white;              /* Text color */
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
   

    .btn-print:hover {
        background-color: #0056b3; /* Darker blue on hover */
        border-color: #004085;      /* Darker border color */
    }

    /* Optional: You can also change the button size and other styles if needed */
    .btn-print {
        padding: 10px 20px;
    }



      .card {
        margin-top: -700px;
        margin-left: auto;
        margin-right: auto;
        display: block;
        width: 130%;
        max-width: 1500px;
      }

      .printable {
        text-align: center;
        margin: 0 auto;
        width: 80%;
      }

      @media print {
        /* Hide elements that shouldn't be printed */
        .action-column,
        .btn {
          display: none;
        }

        /* Table style for print */
        table {
          margin: 0 auto;
          width: 80%;
          border-collapse: collapse;
        }

        table th,
        table td {
          border: 1px solid #ddd;
          padding: 8px;
          text-align: center;
        }

        body {
          text-align: center;
        }

        .printable {
          margin-top: 20px;
        }

        h3 {
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
                                <h4 class="card-title mb-sm-0">Regular Students</h4>
                                <!-- Print button -->
                                <button onclick="printList()" class="btn btn-success mb-4 ml-auto">Print</button>
                            </div>

                            <div class="table-responsive border rounded p-1 printable" id="studentTable">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Student ID</th>
                                            <th>Student Class</th>
                                            <th>Student Name</th>
                                            <th>Student Email</th>
                                            <th>Admission Date</th>
                                            <th class="action-column">Action</th>
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
                                        $ret = "SELECT COUNT(*) as total FROM tblstudent WHERE StudentStatus = 'regular'";
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
                                                WHERE tblstudent.StudentStatus = 'regular' 
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
                                                    <td class="action-column">
                                                       <!-- View Student button -->
                                                        <a href="view_student.php?id=<?php echo htmlentities($row->sid); ?>" class="btn btn-info btn-sm">View</a>
   
                                                        <!-- Delete Student button -->
                                                        <a href="regular_students.php?delid=<?php echo ($row->sid); ?>"
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
        function printList() {
            // Hide action column
            var table = document.getElementById("studentTable");
            var rows = table.getElementsByTagName("tr");

            // Hide the header of the action column
            var headerCells = table.getElementsByTagName("th");
            if (headerCells.length > 0) {
                headerCells[6].style.display = "none"; // Hide the Action column header
            }

            // Loop through all rows and hide the action column (the last column in each row)
            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                if (cells.length > 0) {
                    cells[cells.length - 1].style.display = "none"; // Hide last cell (Action buttons)
                }
            }

            // Get the HTML content for printing
            var printContents = table.outerHTML;
            var originalContents = document.body.innerHTML;

            // Open the print window and write the table content
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Class List</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { text-align: center; font-family: Arial, sans-serif; }'); // Center align the content
            printWindow.document.write('table { margin: 0 auto; width: 80%; border-collapse: collapse; }'); // Center the table and set its width
            printWindow.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }'); // Style the table cells
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h3>Regular Student List</h3>');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Print the content
            printWindow.print();

            // Restore the action column visibility after printing
            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                if (cells.length > 0) {
                    cells[cells.length - 1].style.display = ""; // Restore the last cell (Action buttons)
                }
            }

            // Restore the action column header visibility after printing
            if (headerCells.length > 0) {
                headerCells[6].style.display = ""; // Restore the Action column header
            }
        }
    </script>
</body>

</html>
<?php } ?> 
