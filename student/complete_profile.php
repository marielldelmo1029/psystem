<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $stuname = $_POST['stuname'];
        $stuemail = $_POST['stuemail'];
        $stuclass = $_POST['stuclass'];
        $section = $_POST['section']; // New Section field
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $stuid = $_POST['stuid'];
        $fname = $_POST['fname'];
        $mname = $_POST['mname'];
        $connum = $_POST['connum'];
        $altconnum = $_POST['altconnum'];
        $address = $_POST['address'];
        $uname = $_POST['uname'];
        $password = md5($_POST['password']);
        $image = $_FILES["image"]["name"];
        
        $ret = "SELECT UserName FROM tblstudent WHERE UserName = :uname || StuID = :stuid";
        $query = $dbh->prepare($ret);
        $query->bindParam(':uname', $uname, PDO::PARAM_STR);
        $query->bindParam(':stuid', $stuid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() == 0) {
            $extension = substr($image, strlen($image) - 4, strlen($image));
            $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
            if (!in_array($extension, $allowed_extensions)) {
                echo "<script>alert('Photo has an invalid format. Only jpg / jpeg / png / gif format allowed');</script>";
            } else {
                $image = md5($image) . time() . $extension;
                move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $image);

                // Insert query with Section included
                $sql = "INSERT INTO tblstudent(StudentName, StudentEmail, StudentClass, Section, Gender, DOB, StuID, FatherName, MotherName, ContactNumber, AltenateNumber, Address, UserName, Password, Image) 
                        VALUES(:stuname, :stuemail, :stuclass, :section, :gender, :dob, :stuid, :fname, :mname, :connum, :altconnum, :address, :uname, :password, :image)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':stuname', $stuname, PDO::PARAM_STR);
                $query->bindParam(':stuemail', $stuemail, PDO::PARAM_STR);
                $query->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
                $query->bindParam(':section', $section, PDO::PARAM_STR); // Bind Section
                $query->bindParam(':gender', $gender, PDO::PARAM_STR);
                $query->bindParam(':dob', $dob, PDO::PARAM_STR);
                $query->bindParam(':stuid', $stuid, PDO::PARAM_STR);
                $query->bindParam(':fname', $fname, PDO::PARAM_STR);
                $query->bindParam(':mname', $mname, PDO::PARAM_STR);
                $query->bindParam(':connum', $connum, PDO::PARAM_STR);
                $query->bindParam(':altconnum', $altconnum, PDO::PARAM_STR);
                $query->bindParam(':address', $address, PDO::PARAM_STR);
                $query->bindParam(':uname', $uname, PDO::PARAM_STR);
                $query->bindParam(':password', $password, PDO::PARAM_STR);
                $query->bindParam(':image', $image, PDO::PARAM_STR);
                $query->execute();
                $LastInsertId = $dbh->lastInsertId();
                if ($LastInsertId > 0) {
                    echo '<script>alert("Student has been added.")</script>';
                    echo "<script>window.location.href ='add_students.php'</script>";
                } else {
                    echo '<script>alert("Something went wrong. Please try again.")</script>';
                }
            }
        } else {
            echo "<script>alert('Username or Student ID already exists. Please try again.');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Management System | Add Students</title>
    <link rel="stylesheet" href="css/dashboard.css" />
    <link rel="stylesheet" href="css/sidebar.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
 
    <style>
      .main-panel {
    margin-top:-660px; /* Adjust margin-top if needed */
    margin-left: auto;
    margin-right: auto;
    display: block;
    width: 100%;
    max-width: 900px;
      }
    .dropdown-menu {
    background-color: #f3f7fc; /* Match the background */
    border: 1px solid #d6e0f5;
    margin-left: -100px;
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
                            <h4 class="card-title" style="text-align: center;">Add Students</h4>
                            <form class="forms-sample" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="exampleInputName1">Student Name</label>
                                    <input type="text" name="stuname" value="" class="form-control" required="true">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName1">Student Email</label>
                                    <input type="text" name="stuemail" value="" class="form-control" required="true">
                                </div>
                               
                                <div class="form-group">
    <label for="exampleInputEmail3">Student Class</label>
    <select name="stuclass" class="form-control" required="true">
        <option value="">Select Class</option>
        <?php 
        $sql2 = "SELECT * FROM tblclass";
        $query2 = $dbh->prepare($sql2);
        $query2->execute();
        $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

        foreach ($result2 as $row1) { ?>  
            <option value="<?php echo htmlentities($row1->ID); ?>">
                <?php echo htmlentities($row1->ClassName . ' - ' . $row1->Section); ?>
            </option>
        <?php } ?>
    </select>
</div>

                                <div class="form-group">
                                    <label for="exampleInputName1">Gender</label>
                                    <select name="gender" class="form-control" required="true">
                                        <option value="">Choose Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName1">Date of Birth</label>
                                    <input type="date" name="dob" value="" class="form-control" required="true">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName1">Student ID</label>
                                    <input type="text" name="stuid" value="" class="form-control" required="true">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName1">Student Photo</label>
                                    <input type="file" name="image" value="" class="form-control" required="true">
                                </div>
                                <!-- Remaining fields for parents/guardian details and login details -->

                                
<h3>Login details</h3>
<div class="form-group">
                        <label for="exampleInputName1">User Name</label>
                        <input type="text" name="uname" value="" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Password</label>
                        <input type="Password" name="password" value="" class="form-control" required='true'>
                      </div>
                      <button type="submit" class="btn btn-primary mr-2" name="submit">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
</body>\
<script src="js/bootstrap.bundle.min.js"></script>
</html>
<?php } ?>
