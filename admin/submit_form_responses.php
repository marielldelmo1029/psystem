<?php
session_start();
include('includes/dbconnection.php');

// Check if student ID exists in the session
if (!isset($_SESSION['student_id'])) {
    echo '<script>alert("Student ID is missing. Please log in again.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit();
}

$student_id = $_SESSION['student_id']; // Retrieve student ID from session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $dbh->beginTransaction();

        // Check if the form is submitted and contains responses
        if (!empty($_POST)) {
            // Loop through each field in the form and save the responses
            foreach ($_POST as $field_name => $response) {
                if ($field_name !== 'submit_responses') {
                    // Fetch the field ID from the form_fields table
                    $sql = "SELECT id, required FROM form_fields WHERE field_name = :field_name";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':field_name', $field_name, PDO::PARAM_STR);
                    $query->execute();
                    $field = $query->fetch(PDO::FETCH_OBJ);

                    if ($field) {
                        // Check if the "Mother's Maiden Surname" field is required and empty
                        if ($field->required == 'Yes' && empty($response)) {
                            if ($field_name == "Mother's Maiden Surname") {
                                echo '<script>alert("Please fill in the required field: Mother\'s Maiden Surname");</script>';
                            } else {
                                echo '<script>alert("Please fill in the required field: ' . htmlspecialchars($field_name) . '");</script>';
                            }
                            $dbh->rollBack();
                            exit();
                        }

                        // Check if a response already exists for the student and field
                        $check_sql = "SELECT id FROM student_form_responses WHERE student_id = :student_id AND field_id = :field_id";
                        $stmt_check = $dbh->prepare($check_sql);
                        $stmt_check->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                        $stmt_check->bindParam(':field_id', $field->id, PDO::PARAM_INT);
                        $stmt_check->execute();

                        if ($stmt_check->rowCount() > 0) {
                            // If a response exists, update it
                            $update_sql = "UPDATE student_form_responses SET response = :response WHERE student_id = :student_id AND field_id = :field_id";
                            $stmt_update = $dbh->prepare($update_sql);
                            $stmt_update->bindParam(':response', $response, PDO::PARAM_STR);
                            $stmt_update->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                            $stmt_update->bindParam(':field_id', $field->id, PDO::PARAM_INT);
                            $stmt_update->execute();
                        } else {
                            // If no response exists, insert a new one
                            $sql_response = "INSERT INTO student_form_responses (student_id, field_id, response) 
                                             VALUES (:student_id, :field_id, :response)";
                            $query_response = $dbh->prepare($sql_response);
                            $query_response->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                            $query_response->bindParam(':field_id', $field->id, PDO::PARAM_INT);
                            $query_response->bindParam(':response', $response, PDO::PARAM_STR);
                            $query_response->execute();
                        }
                    }
                }
            }

            $dbh->commit();
            echo '<script>alert("Responses have been submitted successfully.");</script>';
            echo '<script>window.location.href = "dashboard.php";</script>';
        } else {
            echo '<script>alert("No responses to submit.");</script>';
            $dbh->rollBack();
        }
    } catch (PDOException $e) {
        $dbh->rollBack();
        echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
    }
}
?>
