<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
    exit;
} else {
    // Check if the student ID is passed
    if (isset($_GET['id'])) {
        $studentID = intval($_GET['id']); // Ensure it's an integer
        
        // Prepare and execute delete query
        try {
            $sql = "DELETE FROM tblstudent WHERE ID = :id";
            $query = $dbh->prepare($sql);
            $query->bindValue(':id', $studentID, PDO::PARAM_INT);
            $query->execute();

            // Check if the deletion was successful
            if ($query->rowCount() > 0) {
                // Redirect back to the student search page or any other page
                echo "<script>alert('Student record deleted successfully');</script>";
                echo "<script>window.location.href = 'search.php';</script>"; // Modify as needed
            } else {
                // No record found to delete
                echo "<script>alert('No record found for deletion');</script>";
                echo "<script>window.location.href = 'search.php';</script>";
            }
        } catch (Exception $e) {
            // Handle any exceptions
            echo "<script>alert('Error occurred while deleting the record');</script>";
            echo "<script>window.location.href = 'search.php';</script>";
        }
    } else {
        // Redirect if no ID is passed
        echo "<script>window.location.href = 'search.php';</script>";
    }
}
?>
