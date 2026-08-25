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
        $sql = "DELETE FROM tblclass WHERE ID=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Data deleted');</script>";
        echo "<script>window.location.href = 'manage_class.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System | Manage Class</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">

    <style>
        body {
            overflow: hidden;
        }

        .card {
            margin-top: -700px;
            margin-left: auto;
            margin-right: auto;
            display: block;
            width: 100%;
            max-width: 1500px;
        }

        .pagination button {
            margin: 0 5px;
        }

        .pagination .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .pagination .btn:hover {
            background-color: #0056b3;
        }

        .pagination .btn.disabled {
            background-color: #d6d6d6;
            cursor: not-allowed;
        }

        .table-hover tbody tr:hover {
            background-color: #cce5ff;
        }

        .table th,
        .table td {
            text-align: center;
        }

        /* Print specific styles */
        @media print {
            .btn-custom,
            .pagination,
            .action-column {
                display: none;
            }
        }
    </style>

</head>

<body>
    <div class="container-fluid">
        <?php include_once('includes/header.php'); ?>
    </div>
    <!-- Sidebar -->
    <?php include_once('includes/sidebar.php'); ?>

    <!-- Content Wrapper -->
    <div class="main-panel flex-grow-1">
        <div class="content-wrapper mx-auto p-4">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-sm-flex align-items-center mb-4">
                                <h4 class="card-title mb-sm-0">Manage Class</h4>
                                <button onclick="printList()" class="btn btn-success mb-4 ml-auto">Print</button> <!-- Print button -->
                            </div>

                            <div class="table-responsive border rounded p-1">
                                <table class="table table-hover" id="classTable">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold">No.</th>
                                            <th class="font-weight-bold">Class Name</th>
                                            <th class="font-weight-bold">Section</th>
                                            <th class="font-weight-bold">Creation Date</th>
                                            <th class="font-weight-bold action-column">Action</th> <!-- Action column for buttons -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Fetch all classes without pagination
                                        $sql = "SELECT * FROM tblclass";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        $row_number = 1; // Start row numbering
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlentities($row_number); ?></td>
                                                    <td><?php echo htmlentities($row->ClassName); ?></td>
                                                    <td><?php echo htmlentities($row->Section); ?></td>
                                                    <td><?php echo htmlentities($row->CreationDate); ?></td>
                                                    <td class="action-column">
                                                        <a href="view_class.php?classID=<?php echo htmlentities($row->ID); ?>" class="btn btn-info btn-sm">View Students</a>
                                                        <a href="manage_class.php?delid=<?php echo ($row->ID); ?>" onclick="return confirm('Do you really want to delete?');" class="btn btn-danger btn-sm">Delete</a>
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

                            <!-- Pagination Links -->
                            <div align="center">
                                <div class="btn-group">
                                    <!-- Pagination buttons here if needed -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include_once('includes/footer.php'); ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="js/bootstrap.bundle.min.js"></script>

<!-- JavaScript for print function --><script>
    function printList() {
        // Hide the action column and buttons from the print view
        var table = document.getElementById("classTable");
        var rows = table.getElementsByTagName("tr");

        // Hide the header of the action column
        var headerCells = table.getElementsByTagName("th");
        if (headerCells.length > 0) {
            headerCells[headerCells.length - 1].style.display = "none"; // Hide the Action column header
        }

        // Loop through all rows and hide the action column (the last column in each row)
        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName("td");
            if (cells.length > 0) {
                cells[cells.length - 1].style.display = "none"; // Hide last cell (Action button)
            }
        }

        // Get the HTML content for printing
        var printContents = table.outerHTML;
        var originalContents = document.body.innerHTML;

        // Open the print window and write the table content
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Class List</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { text-align: center; }'); // Center align the content
        printWindow.document.write('table { margin: 0 auto; width: 80%; }'); // Center the table and set its width
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h3>Complete Class List</h3>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        // Print the content
        printWindow.print();

        // Restore the action column visibility after printing
        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName("td");
            if (cells.length > 0) {
                cells[cells.length - 1].style.display = ""; // Restore the last cell (Action button)
            }
        }

        // Restore the action column header visibility after printing
        if (headerCells.length > 0) {
            headerCells[headerCells.length - 1].style.display = ""; // Restore the Action column header
        }
    }
</script>


</body>

</html>
<?php } ?>
