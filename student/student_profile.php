<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsstuid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Management System || View Student Profile</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/sidebar.css" />

    <style>
        body{
            background: lightblue;
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
        .main-panel {
            margin: 5% auto;
            max-width: 80%;
            margin-left: 20%;
        }
        .card {
            width: 80%;
            padding: 20px;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .profile-pic img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .btn-print {
            margin-top: 20px;
            margin-left: 10%;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            font-size: 16px;
        }
        table th, table td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 12px;
        }
        table th {
            background-color: #f8f9fa;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        @media print {
            body {
                font-size: 12px;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    <script>
        function printProfile() {
            const originalContents = document.body.innerHTML;
            const printContents = document.getElementById('printArea').innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload(); // Reload page to restore functionality
        }
    </script>
</head>
<body>
    <div class="container-scroller">
        <!-- Include Navbar -->
        <?php include_once('includes/header.php'); ?>
        <!-- Include Sidebar -->
        <?php include_once('includes/sidebar.php'); ?>

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="card" id="printArea">
                    <div class="card-body text-center">
                        <?php
                        $sid = $_SESSION['sturecmsstuid'];
                        $sql = "SELECT tblstudent.StudentName, tblstudent.StudentEmail, tblstudent.StudentClass, tblstudent.Gender,
                                tblstudent.DOB, tblstudent.StuID, tblstudent.FatherName, tblstudent.MotherName,
                                tblstudent.ContactNumber, tblstudent.AltenateNumber, tblstudent.Address, tblstudent.UserName,
                                tblstudent.Image, tblstudent.DateofAdmission, tblclass.ClassName, tblclass.Section 
                                FROM tblstudent 
                                JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                                WHERE tblstudent.StuID = :sid";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                        if ($query->rowCount() > 0) {
                            foreach ($results as $row) { 
                                // Calculate Age
                                $dob = new DateTime($row->DOB);
                                $today = new DateTime();
                                $age = $today->diff($dob)->y;
                        ?>
                                <div class="profile-pic">
                                    <img src="images/<?php echo $row->Image; ?>" alt="Profile Picture">
                                </div>
                                <h3 class="text-primary"><?php echo $row->StudentName; ?></h3>
                                <table>
                                    <tbody>
                                        <tr>
                                            <th>Student Email</th>
                                            <td><?php echo $row->StudentEmail; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Student Class</th>
                                            <td><?php echo $row->ClassName . ' ' . $row->Section; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Gender</th>
                                            <td><?php echo $row->Gender; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth</th>
                                            <td><?php echo $row->DOB; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Age</th>
                                            <td><?php echo $age; ?> years old</td>
                                        </tr>
                                        <tr>
                                            <th>Student ID</th>
                                            <td><?php echo $row->StuID; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Father's Name</th>
                                            <td><?php echo $row->FatherName; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Mother's Name</th>
                                            <td><?php echo $row->MotherName; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Contact Number</th>
                                            <td><?php echo $row->ContactNumber; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Alternate Number</th>
                                            <td><?php echo $row->AltenateNumber; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td><?php echo $row->Address; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Date of Admission</th>
                                            <td><?php echo $row->DateofAdmission; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                        <?php
                            }
                        } else {
                            echo "<p class='text-danger'>No record found!</p>";
                        }

                        // Fetch student responses to the form fields
                        $sql_responses = "SELECT form_fields.label, student_form_responses.response 
                                          FROM student_form_responses 
                                          JOIN form_fields ON form_fields.id = student_form_responses.field_id
                                          WHERE student_form_responses.student_id = :sid";
                        $stmt_responses = $dbh->prepare($sql_responses);
                        $stmt_responses->bindParam(':sid', $sid, PDO::PARAM_STR);
                        $stmt_responses->execute();
                        $responses = $stmt_responses->fetchAll(PDO::FETCH_ASSOC);

                        if ($responses) {
                            echo "<h4></h4><table><tbody>";
                            foreach ($responses as $response) {
                                echo "<tr>
                                        <th>" . htmlspecialchars($response['label']) . "</th>
                                        <td>" . htmlspecialchars($response['response']) . "</td>
                                      </tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<p>No additional responses found.</p>";
                        }
                        ?>
                    </div>
                </div>
                <button class="btn btn-primary btn-print" onclick="printProfile()">Print Profile</button>
            </div>
        </div>
    </div>
</body>
</html>
<?php } ?>
