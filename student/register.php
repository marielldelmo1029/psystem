<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['register'])) {
    // Capture form inputs
    $name = $_POST['name'];
    $stuid = $_POST['stuid'];
    $password = md5($_POST['password']);
    $confirm_password = md5($_POST['confirm_password']);
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $stuclass = $_POST['stuclass'];
    $dob = $_POST['dob'];

    // Check if passwords match
    if ($password != $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        try {
            // Check if the Student ID or Email is already registered
            $checkSql = "SELECT * FROM tblstudent WHERE StuID = :stuid OR StudentEmail = :email";
            $checkStmt = $dbh->prepare($checkSql);
            $checkStmt->bindParam(':stuid', $stuid, PDO::PARAM_STR);
            $checkStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $checkStmt->execute();
            $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                echo "<script>alert('Student ID or Email is already registered. Please use a different one.');</script>";
            } else {
                // Insert new student data into the database with status as 'pending'
                $sql = "INSERT INTO tblstudent (StudentName, StuID, Password, StudentEmail, ContactNumber, Address, StudentClass, DOB, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
                $stmt = $dbh->prepare($sql);
                $stmt->execute([$name, $stuid, $password, $email, $contact_number, $address, $stuclass, $dob]);

                // Success message
                echo "<script>alert('Registration successful. Your account is pending approval. Please wait for an administrator to approve your account.');</script>";
                echo "<script>window.location.href='login.php';</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Student Management System | Registration</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .form-container {
            background-color: lightblue;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 500px;
        }

        h4 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .auth-link {
            color: #007bff;
            text-decoration: none;
        }

        .auth-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="form-container text-center">
        <h4 class="mb-3">Register New Student</h4>
        <form method="post" name="register">
            <div class="form-group">
                <input type="text" placeholder="Full Name" name="name" required>
            </div>
            <div class="form-group">
                <input type="text" placeholder="Student ID" name="stuid" required>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Confirm Password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <input type="email" placeholder="Email" name="email" required>
            </div>
            <div class="form-group">
                <input type="text" placeholder="Contact Number" name="contact_number" required>
            </div>
            <div class="form-group">
                <textarea placeholder="Address" name="address" required></textarea>
            </div>
            <div class="form-group">
                <select name="stuclass" class="form-control" required>
                    <option value="">Select Class</option>
                    <?php
                    $sql2 = "SELECT * FROM tblclass";
                    $query2 = $dbh->prepare($sql2);
                    $query2->execute();
                    $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                    foreach ($result2 as $row1) {
                    ?>
                        <option value="<?php echo htmlentities($row1->ID); ?>">
                            <?php echo htmlentities($row1->ClassName . ' - ' . $row1->Section); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <p>Enter Birthdate</p>
                <input type="date" placeholder="Date of Birth" name="dob" required>
            </div>
            <button type="submit" name="register">Register</button>
            <div class="mt-3">
                <a href="login.php" class="auth-link">Already have an account? Login</a>
            </div>
        </form>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
