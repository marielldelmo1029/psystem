<?php
session_start();
include('includes/dbconnection.php');

// Check if admin is logged in
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    // Check if classID is passed in the URL
    if (isset($_GET['classID'])) {
        $classID = intval($_GET['classID']); // Ensure it's an integer
    } else {
        echo "Invalid or missing class ID.";
        exit;
    }

    // Fetch ClassName and Section from tblclass based on the classID
    try {
        $sqlClass = "SELECT ClassName, Section FROM tblclass WHERE ID = :classID";
        $stmtClass = $dbh->prepare($sqlClass);
        $stmtClass->bindParam(':classID', $classID, PDO::PARAM_INT);
        $stmtClass->execute();
        $classData = $stmtClass->fetch(PDO::FETCH_ASSOC);

        if (!$classData) {
            echo "Class not found.";
            exit;
        }

        $className = $classData['ClassName'];
        $section = $classData['Section'];
    } catch (PDOException $e) {
        echo "Error fetching class data: " . $e->getMessage();
        exit;
    }

    // Fetch students based on the provided classID
    try {
        $sqlStudents = "SELECT tblstudent.* 
                        FROM tblstudent 
                        INNER JOIN tblclass 
                        ON tblstudent.StudentClass = tblclass.ID
                        WHERE tblstudent.StudentClass = :classID";
        $stmtStudents = $dbh->prepare($sqlStudents);
        $stmtStudents->bindParam(':classID', $classID, PDO::PARAM_INT);
        $stmtStudents->execute();
        $students = $stmtStudents->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching students: " . $e->getMessage();
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students in <?php echo htmlspecialchars($className . ' ' . $section); ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .container {
            margin-top: -660px;
            margin-left: auto;
            margin-right: auto;
            display: block;
            width: 100%;
            max-width: 900px;
        }

        /* Print specific styles */
        @media print {
            .btn-print {
                display: none; /* Hide the Print button during print */
            }

            /* Hide the action column during printing */
            th:nth-child(5), td:nth-child(5) {
                display: none; /* Hide the action column (including the header and data) */
            }

            table {
                width: 80%;
                margin: 0 auto;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <?php include_once('includes/header.php'); ?>
    </div>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="container">
        <h2 class="text-center mt-4">Students in <?php echo htmlspecialchars($className . ' ' . $section); ?></h2>

        <!-- Print Button -->
        <button onclick="printList()" class="btn btn-success btn-print mb-4">Print List</button>

        <table class="table table-bordered mt-4" id="studentTable">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th class="action-column">Action</th> <!-- Action column will be hidden during print -->
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($students) > 0) {
                    foreach ($students as $student) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($student['StuID']) . "</td>";
                        echo "<td>" . htmlspecialchars($student['StudentName']) . "</td>";
                        echo "<td>" . htmlspecialchars($student['StudentEmail']) . "</td>";
                        echo "<td>" . htmlspecialchars($student['ContactNumber']) . "</td>";
                        echo "<td class='action-column'><a href='view_student.php?id=" . $student['ID'] . "' class='btn btn-info btn-sm'>View</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No students found for this class.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="manage_class.php" class="btn btn-primary mt-4">Back to Class List</a>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>

    <script>
        function printList() {
            // Hide the Action column (both the header and the column data)
            var table = document.getElementById('studentTable');
            var rows = table.getElementsByTagName('tr');
            
            // Loop through all rows (including the header row) and hide the action column
            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName('td');
                var headers = rows[i].getElementsByTagName('th');  // Handle header row too
                if (cells.length > 0) {
                    // Hide the last column (Action column)
                    cells[cells.length - 1].style.display = 'none';
                }
                if (headers.length > 0) {
                    headers[headers.length - 1].style.display = 'none'; // Hide header column
                }
            }

            // Get the HTML content for printing
            var printContents = table.outerHTML;
            var originalContents = document.body.innerHTML;

            // Open the print window and write the table content
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Class Students List</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { text-align: center; }'); // Center align the content
            printWindow.document.write('table { width: 80%; margin: 0 auto; text-align: center; }'); // Center the table and set width
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h3>Complete Class Students List</h3>');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Print the content
            printWindow.print();
        }
    </script>
</body>

</html>

<?php
}
?>
