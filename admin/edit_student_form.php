<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    // Get student ID from URL
    if (isset($_GET['editid'])) {
        $student_id = $_GET['editid'];

        // Fetch student data to fill the form
        $sql = "SELECT * FROM tblstudent WHERE StuID = :student_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$student) {
            echo "<script>alert('Student not found');</script>";
            exit;
        }
    }

    // Save the updated data if form is submitted
    if (isset($_POST['update'])) {
        $student_name = $_POST['student_name'];
        $student_email = $_POST['student_email'];
        $contact_number = $_POST['contact_number'];
        $student_class = $_POST['student_class'];
        $status = $_POST['status'];

        // Update student data in the database
        $sql = "UPDATE tblstudent SET StudentName = :student_name, StudentEmail = :student_email, ContactNumber = :contact_number, StudentClass = :student_class, status = :status WHERE StuID = :student_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':student_name', $student_name, PDO::PARAM_STR);
        $stmt->bindParam(':student_email', $student_email, PDO::PARAM_STR);
        $stmt->bindParam(':contact_number', $contact_number, PDO::PARAM_STR);
        $stmt->bindParam(':student_class', $student_class, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);

        $stmt->execute();
        echo "<script>alert('Student data updated successfully');</script>";
        echo "<script>window.location.href='manage_students.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Form</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <div class="container-fluid">
        <?php include('includes/header.php'); ?>
    </div>
    <?php include('includes/sidebar.php'); ?>


    <div class="main-panel flex-grow-1">
        <div class="content-wrapper mx-auto p-4">
            <h4>Edit Student Information</h4>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="student_name">Student Name</label>
                    <input type="text" class="form-control" id="student_name" name="student_name" value="<?php echo htmlspecialchars($student['StudentName']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="student_email">Student Email</label>
                    <input type="email" class="form-control" id="student_email" name="student_email" value="<?php echo htmlspecialchars($student['StudentEmail']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($student['ContactNumber']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="student_class">Student Class</label>
                    <select class="form-control" id="student_class" name="student_class" required>
                        <?php
                        // Fetch all classes from the database
                        $class_query = "SELECT * FROM tblclass";
                        $class_stmt = $dbh->prepare($class_query);
                        $class_stmt->execute();
                        $classes = $class_stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Check if the student's class matches any in the list
                        foreach ($classes as $class) {
                            $selected = ($student['StudentClass'] == $class['ID']) ? "selected" : "";
                            echo "<option value='" . $class['ID'] . "' $selected>" . htmlspecialchars($class['ClassName']) . " " . htmlspecialchars($class['Section']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="active" <?php echo ($student['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="pending" <?php echo ($student['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>

                <button type="submit" name="update" class="btn btn-primary">Update Student</button>
                <a href="manage_students.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>
