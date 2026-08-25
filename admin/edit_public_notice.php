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
 $eid=$_GET['editid'];
$sql="update tblpublicnotice set NoticeTitle=:nottitle,NoticeMessage=:notmsg where ID=:eid";
$query=$dbh->prepare($sql);
$query->bindParam(':nottitle',$nottitle,PDO::PARAM_STR);
$query->bindParam(':notmsg',$notmsg,PDO::PARAM_STR);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
 $query->execute();
  echo '<script>alert("Notice has been updated")</script>';
}

  ?>
<!DOCTYPE html>
<html lang="en">
  <head>
   
    <title>Student  Management System|| Update Notice</title>
  
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/dashboard.css" />

    
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
      <?php include_once('includes/sidebar.php');?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
           
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title" style="text-align: center;">Update Public Notice</h4>
                   
                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                      <?php
$eid=$_GET['editid'];
$sql="SELECT * from tblpublicnotice where ID=:eid";
$query = $dbh -> prepare($sql);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                      <div class="form-group">
                        <label for="exampleInputName1">Notice Title</Title></label>
                        <input type="text" name="nottitle" value="<?php  echo htmlentities($row->NoticeTitle);?>" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1"> Message</label>
                        <textarea name="notmsg" value="" class="form-control" required='true'><?php  echo htmlentities($row->NoticeMessage);?></textarea>
                      </div>
                   <?php $cnt=$cnt+1;}} ?>
                      <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                     
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
       
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
   <script src="js/bootstrap.bundle.min.js"></script>
  </body>
</html><?php }  ?>