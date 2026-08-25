<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
    $stuid = $_POST['stuid'];
    $password = md5($_POST['password']);
    // SQL query to check student credentials and status
    $sql = "SELECT StuID, ID, StudentClass, status FROM tblstudent WHERE (UserName=:stuid OR StuID=:stuid) AND Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':stuid', $stuid, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            // Check if the student status is 'active'
            if ($result->status == 'active') {
                // Proceed with login
                $_SESSION['sturecmsstuid'] = $result->StuID;
                $_SESSION['sturecmsuid'] = $result->ID;
                $_SESSION['stuclass'] = $result->StudentClass;

                if (!empty($_POST["remember"])) {
                    setcookie("user_login", $_POST["stuid"], time() + (10 * 365 * 24 * 60 * 60));  // Keep the user logged in
                    setcookie("userpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60));  // Keep password stored
                } else {
                    if (isset($_COOKIE["user_login"])) {
                        setcookie("user_login", "", time() - 3600);  // Clear cookies if not 'remember me'
                        setcookie("userpassword", "", time() - 3600);
                    }
                }

                $_SESSION['login'] = $_POST['stuid'];  // Store the logged-in username
                echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";  // Redirect to dashboard after login
            } elseif ($result->status == 'pending') {
                // Account is pending for admin approval
                echo "<script>alert('Account pending for admin\'s approval.');</script>";
            } else {
                // Account is inactive or suspended
                echo "<script>alert('Your account is inactive or suspended. Please contact the administrator.');</script>";
            }
        }
    } else {
        // If the account is not found in the database
        echo "<script>alert('Registration disapproved or not found in the database. Please check your credentials or contact the administrator.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System | Login Page</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
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
            text-decoration: underline;
        }

        .brand-logo img {
            width: 100px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container text-center">
        <h4 class="mb-3">Welcome Back!</h4>
        <h6 class="mb-4 text-muted">Sign in to continue.</h6>
        <form id="login" method="post" name="login">
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Enter your student ID or username" required name="stuid" value="<?php if (isset($_COOKIE['user_login'])) echo $_COOKIE['user_login']; ?>">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" placeholder="Enter your password" required name="password" value="<?php if (isset($_COOKIE['userpassword'])) echo $_COOKIE['userpassword']; ?>">
            </div>
            <div class="form-check d-flex align-items-center justify-content-start mb-3">
                <input class="form-check-input" type="checkbox" id="remember" name="remember" <?php if (isset($_COOKIE["user_login"])) echo "checked"; ?>>
                <label class="form-check-label ms-2" for="remember">Keep me signed in</label>
            </div>
            <button type="submit" class="btn btn-primary btn-block w-100" name="login">Login</button>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <a href="forgot_password.php" class="auth-link">Forgot password?</a>
                <a href="../index.php" class="auth-link">Back Home</a>
            </div>
        </form>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
