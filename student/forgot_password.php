<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = md5($_POST['newpassword']);
    $sql = "SELECT StudentEmail FROM tblstudent WHERE StudentEmail=:email and ContactNumber=:mobile";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        $con = "UPDATE tblstudent SET Password=:newpassword WHERE StudentEmail=:email and ContactNumber=:mobile";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
        $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();
        echo "<script>alert('Your Password successfully changed');</script>";
    } else {
        echo "<script>alert('Email id or Mobile no is invalid');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Management System | Forgot Password</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #007bff, #0056b3);
            color: white;
        }

        .auth-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #0056b3;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #003d80;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .auth-link {
            color: #007bff;
            text-decoration: none;
        }

        .auth-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .auth-btn {
            background-color: #007bff;
            color: white;
        }

        .auth-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 auth-card">
            <h4 class="text-center mb-4">Recover Password</h4>
            <p class="text-center text-muted mb-4">Enter your email address and mobile number to reset your password.</p>
            <form method="post" onsubmit="return valid();">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile Number</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter your mobile number" required pattern="[0-9]+" maxlength="10">
                </div>
                <div class="mb-3">
                    <label for="newpassword" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="Enter new password" required>
                </div>
                <div class="mb-3">
                    <label for="confirmpassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm new password" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" name="submit" class="btn btn-primary">Reset Password</button>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="login.php" class="auth-link">Sign In</a>
                    <a href="../index.php" class="auth-link">Back Home</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function valid() {
            const newPassword = document.getElementById('newpassword').value;
            const confirmPassword = document.getElementById('confirmpassword').value;
            if (newPassword !== confirmPassword) {
                alert("New Password and Confirm Password do not match!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
