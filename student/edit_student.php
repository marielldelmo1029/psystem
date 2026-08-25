<?php
session_start();
include('includes/dbconnection.php');

// Check if the session is valid
if (!isset($_SESSION['sturecmsstuid']) || strlen($_SESSION['sturecmsstuid']) == 0) {
    echo "Session is not valid. Please log in again.";
    exit();
}

$stuid = $_SESSION['sturecmsstuid']; // Retrieve StuID from session

// Fetch student data based on session ID (StuID)
$sql = "SELECT * FROM tblstudent WHERE StuID = ?";
$stmt = $dbh->prepare($sql);
$stmt->execute([$stuid]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "Student ID does not exist in the database. Please contact the administrator.";
    exit();
}

// Function to calculate age from DOB
function calculate_age($dob) {
    $dob = new DateTime($dob);
    $today = new DateTime();
    $interval = $today->diff($dob);
    return $interval->y; // Get years from the interval
}

// Calculate age from DOB
$age = calculate_age($row['DOB']);

// Fetch classes from tblclass table
$sql_classes = "SELECT * FROM tblclass ORDER BY ClassName ASC";
$stmt_classes = $dbh->prepare($sql_classes);
$stmt_classes->execute();
$classes = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);

// Fetch form fields from the database (Admin's dynamic form)
$sql_form = "SELECT id, label, field_name, field_type, options, required FROM form_fields ORDER BY id ASC";
$stmt_form = $dbh->prepare($sql_form);
$stmt_form->execute();
$form_fields = $stmt_form->fetchAll(PDO::FETCH_OBJ);

// Handle form submission for the dynamic form
if (isset($_POST['submit'])) {
    // Capture personal info form data
    $stuname = $_POST['stuname'];
    $stuemail = $_POST['stuemail'];
    $stuclass = $_POST['stuclass'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $connum = $_POST['connum'];
    $altconnum = $_POST['altconnum'];
    $address = $_POST['address'];
    $studentstatus = $_POST['studentstatus'];

    // Handling the image upload
    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    if ($image) {
        $image_folder = 'images/' . $image;
        // Check if the directory exists or create it
        if (!is_dir('images')) {
            mkdir('images', 0777, true);
        }
        move_uploaded_file($image_temp, $image_folder);
    } else {
        $image = $_POST['existing_image'];  // Use the existing image if none is uploaded
    }

    try {
        // Update query to change student details
        $sql = "UPDATE tblstudent 
                SET StudentName = ?, StuID = ?, StudentEmail = ?, StudentClass = ?, Gender = ?, DOB = ?, FatherName = ?, MotherName = ?, 
                    ContactNumber = ?, AltenateNumber = ?, Address = ?, Image = ?, StudentStatus = ? 
                WHERE StuID = ?";

        $stmt = $dbh->prepare($sql); // Using PDO's prepare method
        $stmt->execute([$stuname, $stuid, $stuemail, $stuclass, $gender, $dob, $fname, $mname, $connum, $altconnum, $address, $image, $studentstatus, $stuid]);

        echo "<script>alert('Your information has been updated successfully.');</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error updating information: " . $e->getMessage() . "');</script>";
    }

    // Insert dynamic form responses
    foreach ($form_fields as $field) {
        $response = isset($_POST[$field->field_name]) ? $_POST[$field->field_name] : '';

        // Validate required fields
        if ($field->required == 'Yes' && empty($response)) {
            echo "<script>alert('Please fill in the required field: " . htmlspecialchars($field->label) . "');</script>";
            exit();  // Stop execution if validation fails
        }

        try {
            // Ensure you're inserting responses into the database with the correct data
            $sql_insert = "INSERT INTO student_form_responses (student_id, field_id, response) 
                           VALUES (:student_id, :field_id, :response)";
            $query_insert = $dbh->prepare($sql_insert);
            $query_insert->bindParam(':student_id', $stuid, PDO::PARAM_STR); // Use StuID here
            $query_insert->bindParam(':field_id', $field->id, PDO::PARAM_INT);
            $query_insert->bindParam(':response', $response, PDO::PARAM_STR);
            $query_insert->execute();
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }

    echo "<script>alert('Your responses have been submitted successfully.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/sidebar.css">

    <style>
        .page-content {
            margin-left: auto;
            margin-right: auto;
            display: block;
            width: 100%;
            max-width: 900px;
        }
        body {
            background: lightblue;
        }
 
.page-content {
    margin-left: 25%;
    padding: 20px;
    background-color: #f8f9fa;
    transition: margin-left 0.3s ease; /* Smooth transition for page shift */
}

/* Card Container (for dashboard cards) */
.card-container {
    display: flex; /* Use flexbox to arrange cards in a row */
    flex-wrap: wrap; /* Allow wrapping on smaller screens */
    gap: 20px; /* Space between cards */
    justify-content: flex-start; /* Align cards to the left */
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

/* Form Section */
.form-section {
    margin-top: 30px;
    background-color: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.form-section h4 {
    margin-bottom: 20px;
    font-weight: bold;
}

    </style>
</head>
<body>
<?php include_once('includes/sidebar.php'); ?>
<?php include_once('includes/header.php'); ?>
<br>
<div class="container-scroller">
    <div class="page-content">
        <h4>Update Your Information</h4>
        <form method="post" enctype="multipart/form-data">
            <!-- Personal Information Form -->
            <div class="form-group">
                <label>Student Name</label>
                <input type="text" name="stuname" class="form-control" value="<?php echo htmlspecialchars($row['StudentName']); ?>" required>
            </div>
            <div class="form-group">
                <label>Student ID</label>
                <input type="text" name="stuid" class="form-control" value="<?php echo htmlspecialchars($row['StuID']); ?>" readonly required>
            </div>

            <div class="form-group">
                <label>Student Email</label>
                <input type="email" name="stuemail" class="form-control" value="<?php echo htmlspecialchars($row['StudentEmail']); ?>" required>
            </div>
            <div class="form-group">
                <label>Student Class</label>
                <select name="stuclass" class="form-control" required>
                    <?php foreach ($classes as $class) { ?>
                        <option value="<?php echo $class['ID']; ?>" <?php echo ($row['StudentClass'] == $class['ID']) ? 'selected' : ''; ?>>
                            <?php echo $class['ClassName'] . " - " . $class['Section']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="gender" class="form-control">
                    <option value="Female" <?php echo ($row['Gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    <option value="Male" <?php echo ($row['Gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                </select>
            </div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="<?php echo htmlspecialchars($row['DOB']); ?>" required>
            </div>
        <!-- Father's Name -->
<div class="form-group">
    <label>Father's Name</label>
    <input type="text" name="fname" class="form-control" 
        value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : htmlspecialchars($row['FatherName']); ?>" 
        required>
</div>

<!-- Mother's Name -->
<div class="form-group">
    <label>Mother's Name</label>
    <input type="text" name="mname" class="form-control" 
        value="<?php echo isset($_POST['mname']) ? htmlspecialchars($_POST['mname']) : htmlspecialchars($row['MotherName']); ?>" 
        required>
</div>


            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="connum" class="form-control" value="<?php echo htmlspecialchars($row['ContactNumber']); ?>" required>
            </div>
       <!-- Alternate Contact Number -->
<div class="form-group">
    <label>Alternate Contact Number</label>
    <input type="text" name="altconnum" class="form-control" 
        value="<?php echo isset($_POST['altconnum']) ? htmlspecialchars($_POST['altconnum']) : htmlspecialchars($row['AltenateNumber']); ?>" 
        required>
</div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control" required><?php echo htmlspecialchars($row['Address']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Student Status</label>
                <select name="studentstatus" class="form-control" required>
                    <option value="Drop" <?php echo ($row['StudentStatus'] == 'Drop') ? 'selected' : ''; ?>>Drop</option>
                    <option value="Shift" <?php echo ($row['StudentStatus'] == 'Shift') ? 'selected' : ''; ?>>Shift</option>
                    <option value="Regular" <?php echo ($row['StudentStatus'] == 'Regular') ? 'selected' : ''; ?>>Regular</option>
                    <option value="Irregular" <?php echo ($row['StudentStatus'] == 'Irregular') ? 'selected' : ''; ?>>Irregular</option>
                    <option value="Graduate" <?php echo ($row['StudentStatus'] == 'Graduate') ? 'selected' : ''; ?>>Graduate</option>
                </select>
            </div>
            <div class="form-group">
                <label>Student Image</label>
                <input type="file" name="image" class="form-control">
                <input type="hidden" name="existing_image" value="<?php echo $row['Image']; ?>">
                <!-- Display existing image if available -->
                <?php if ($row['Image']) { ?>
                    <img src="images/<?php echo $row['Image']; ?>" width="100" height="100" alt="Student Image">
                <?php } ?>
            </div>

          <!-- Dynamic Form Responses -->
<?php foreach ($form_fields as $field) { ?>
    <div class="form-group">
        <label><?php echo htmlspecialchars($field->label); ?> <?php echo ($field->required == 'Yes' ? '*' : ''); ?></label>
        
        <?php
        // Fetch the student's response for this field if it exists
        $response = '';
        $sql_response = "SELECT response FROM student_form_responses WHERE student_id = :student_id AND field_id = :field_id";
        $query_response = $dbh->prepare($sql_response);
        $query_response->bindParam(':student_id', $stuid, PDO::PARAM_STR);
        $query_response->bindParam(':field_id', $field->id, PDO::PARAM_INT);
        $query_response->execute();
        $existing_response = $query_response->fetch(PDO::FETCH_ASSOC);
        
        if ($existing_response) {
            $response = $existing_response['response']; // If response exists, assign it
        }
        
        // Handling different field types
        if ($field->field_type == 'text' || $field->field_type == 'email') { 
            echo "<input type='" . ($field->field_type == 'email' ? 'email' : 'text') . "' name='" . htmlspecialchars($field->field_name) . "' class='form-control' 
                  value='" . (isset($_POST[$field->field_name]) ? htmlspecialchars($_POST[$field->field_name]) : htmlspecialchars($response)) . "' 
                  " . ($field->required == 'Yes' ? 'required' : '') . ">";
        } elseif ($field->field_type == 'select') {
            echo "<select name='" . htmlspecialchars($field->field_name) . "' class='form-control' " . ($field->required == 'Yes' ? 'required' : '') . ">";
            $options = explode(",", $field->options);
            foreach ($options as $option) {
                echo "<option value='" . htmlspecialchars($option) . "' " . (isset($_POST[$field->field_name]) && $_POST[$field->field_name] == $option ? 'selected' : ($response == $option ? 'selected' : '')) . ">" . htmlspecialchars($option) . "</option>";
            }
            echo "</select>";
        } elseif ($field->field_type == 'date') {
            // For date fields, use the value from the database if available, or leave it blank
            echo "<input type='date' name='" . htmlspecialchars($field->field_name) . "' class='form-control' 
                  value='" . (isset($_POST[$field->field_name]) ? htmlspecialchars($_POST[$field->field_name]) : htmlspecialchars($response)) . "' 
                  " . ($field->required == 'Yes' ? 'required' : '') . ">";
        }
        ?>
    </div>
<?php } ?>


            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
<br>


</body>
</html>
