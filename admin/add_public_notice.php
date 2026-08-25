<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']==0)) {
  header('location:logout.php');
  } else{
   if(isset($_POST['submit']))
  {
 $nottitle=$_POST['nottitle'];

 $notmsg=$_POST['notmsg'];
$sql="insert into tblpublicnotice(NoticeTitle,NoticeMessage)values(:nottitle,:notmsg)";
$query=$dbh->prepare($sql);
$query->bindParam(':nottitle',$nottitle,PDO::PARAM_STR);
$query->bindParam(':notmsg',$notmsg,PDO::PARAM_STR);
 $query->execute();
   $LastInsertId=$dbh->lastInsertId();
   if ($LastInsertId>0) {
    echo '<script>alert("Notice has been added.")</script>';
echo "<script>window.location.href ='add_public_notice.php'</script>";
  }
  else
    {
         echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
}
  ?>
<!DOCTYPE html>
<html lang="en">
  <head>
   
    <title>Student  Management System|| Add Notice</title>
  
    <!-- Layout styles -->
    <link rel="stylesheet" href="css/dashboard.css" />
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
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
     <?php include_once('includes/header.php');?>
      <!-- partial -->
        <!-- partial:partials/_sidebar.html -->
      <?php include_once('includes/sidebar.php');?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">

            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title" style="text-align: center;">Add Public Notice</h4>
                   
                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                      
                      <div class="form-group">
                        <label for="exampleInputName1">Notice Title</label>
                        <input type="text" name="nottitle" value="" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1"> Message</label>
                        <textarea name="notmsg" value="" class="form-control" required='true'></textarea>
                      </div>
                   
                      <button type="submit" class="btn btn-primary mr-2" name="submit">Add</button>
                     
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
         <?php include_once('includes/footer.php');?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
     <script src="js/bootstrap.bundle.min.js"></script>
  </body>
</html><?php }  ?>