<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Ensure the user is logged in
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    // Check if student ID is passed in the URL
    if (isset($_GET['id'])) {
        $studentID = intval($_GET['id']); // Ensure it's an integer
    } else {
        // Redirect to a relevant page if ID is missing
        header('location:student_list.php');
        exit;
    }

    // Fetch student details based on the provided student ID
    try {
        $sqlStudent = "SELECT tblstudent.*, tblclass.ClassName, tblclass.Section 
                       FROM tblstudent 
                       INNER JOIN tblclass 
                       ON tblstudent.StudentClass = tblclass.ID 
                       WHERE tblstudent.ID = :studentID";
        $stmtStudent = $dbh->prepare($sqlStudent);
        $stmtStudent->bindParam(':studentID', $studentID, PDO::PARAM_INT);
        $stmtStudent->execute();
        $student = $stmtStudent->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching student details: " . $e->getMessage();
        exit;
    }

    // If no student found, redirect back
    if (!$student) {
        echo "Student not found.";
        exit;
    }

    // Calculate Age
    $dob = new DateTime($student['DOB'] ?? '2000-01-01'); // Default if DOB is missing
    $today = new DateTime();
    $age = $today->diff($dob)->y;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student | Student Management System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/dashboard.css" />

    <style>
        .main-panel {
            margin-top: -660px;
            margin-left: auto;
            margin-right: auto;
            display: block;
            width: 100%;
            max-width: 800px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
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

        /* Sidebar fixed to the left */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #f8f9fa;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        .card-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .student-photo {
            display: block;
            margin: 20px auto;
            border-radius: 50%;
            width: 240px;
            height: 240px;
            object-fit: cover;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #6c757d;
        }

        @media print {
            body {
                font-size: 14px;
                color: #000;
                text-align: center;
            }

            table {
                margin: 0 auto;
                width: 90%;
                border-collapse: collapse;
            }

            table th,
            table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            .student-photo {
                width: 150px;
                height: 150px;
                margin: 20px auto;
            }

            .action-column,
            .btn {
                display: none; /* Hide action buttons during print */
            }

            .footer {
                display: none; /* Hide footer during print */
            }
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
                            <h4 class="card-title">Student Details</h4>

                            <!-- Student Photo -->
                            <?php 
                            $imagePath = "../student/images/" . htmlspecialchars($student['Image']);
                            if (file_exists($imagePath) && !empty($student['Image'])) {
                                echo '<img src="' . $imagePath . '" alt="Student Photo" class="student-photo">';
                            } else {
                                echo '<img src="images/default-avatar.png" alt="Default Avatar" class="student-photo">';
                            }
                            ?>

                            <!-- Student Information Table -->
                            <table class="table table-bordered">
                                <tr>
                                    <th>Student Name</th>
                                    <td><?php echo htmlspecialchars($student['StudentName']); ?></td>
                                </tr>
                                <tr>
                                    <th>Student Email</th>
                                    <td><?php echo htmlspecialchars($student['StudentEmail']); ?></td>
                                </tr>
                                <tr>
                                    <th>Class</th>
                                    <td><?php echo htmlspecialchars($student['ClassName']) . ' - ' . htmlspecialchars($student['Section']); ?></td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td><?php echo htmlspecialchars($student['Gender']); ?></td>
                                </tr>
                                <tr>
                                    <th>Date of Birth</th>
                                    <td><?php echo htmlspecialchars($student['DOB']); ?></td>
                                </tr>
                                <tr>
                                    <th>Age</th>
                                    <td><?php echo $age; ?> years old</td>
                                </tr>
                                <tr>
                                    <th>Student ID</th>
                                    <td><?php echo htmlspecialchars($student['StuID']); ?></td>
                                </tr>
                                <tr>
                                    <th>Father's Name</th>
                                    <td><?php echo htmlspecialchars($student['FatherName']); ?></td>
                                </tr>
                                <tr>
                                    <th>Mother's Name</th>
                                    <td><?php echo htmlspecialchars($student['MotherName']); ?></td>
                                </tr>
                                <tr>
                                    <th>Contact Number</th>
                                    <td><?php echo htmlspecialchars($student['ContactNumber']); ?></td>
                                </tr>
                                <tr>
                                    <th>Alternate Number</th>
                                    <td><?php echo htmlspecialchars($student['AltenateNumber']); ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?php echo htmlspecialchars($student['Address']); ?></td>
                                </tr>
                                <!-- Added Student Status -->
                                <tr>
                                    <th>Student Status</th>
                                    <td><?php echo htmlspecialchars($student['StudentStatus']); ?></td>
                                </tr>
                            </table>

                            <!-- Print Button -->
                            <button class="btn btn-success" onclick="printStudentDetails()">Print</button>

                            <br><br>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="search.php" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br><br>

    <script src="js/bootstrap.bundle.min.js"></script>

    <script>
    function printStudentDetails() {
        // Get the current table content and student photo
        var tableContent = document.querySelector('.table').outerHTML;
        var studentPhoto = document.querySelector('.student-photo').outerHTML;

        // Open a new window to print
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-size: 14px; color: #000; text-align: center; }');
        printWindow.document.write('table { margin: 0 auto; width: 80%; border-collapse: collapse; }');
        printWindow.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }');
        printWindow.document.write('.student-photo { width: 150px; height: 150px; margin: 20px auto; display: block; border-radius: 50%; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h3>Student Details</h3>');
        printWindow.document.write(studentPhoto); // Add student photo to the print content
        printWindow.document.write(tableContent);
        printWindow.document.write('</body></html>');

        // Close the document and print the content
        printWindow.document.close();
        printWindow.print();
    }
</script>
</body>
</html>

<?php } ?>
