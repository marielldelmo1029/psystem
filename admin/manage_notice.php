<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    // Code for deletion
    if (isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);
        $sql = "DELETE FROM tblnotice WHERE ID=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Data deleted');</script>";
        echo "<script>window.location.href = 'manage_notice.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Management System | Manage Notice</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .main-panel {
            margin-top: -660px;
            margin-left: 20%;
            margin-right: auto;
            display: block;
            width: 100%;
            max-width: 1000px;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .pagination .btn {
            margin: 0 5px;
        }

        .pagination .btn.disabled {
            background-color: #d6d6d6;
            cursor: not-allowed;
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
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Manage Notice</h4>
                            <div class="table-responsive border rounded p-1">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Notice Title</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>Notice Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_GET['pageno'])) {
                                            $pageno = $_GET['pageno'];
                                        } else {
                                            $pageno = 1;
                                        }
                                        $no_of_records_per_page = 10;
                                        $offset = ($pageno - 1) * $no_of_records_per_page;

                                        $ret = "SELECT ID FROM tblnotice";
                                        $query1 = $dbh->prepare($ret);
                                        $query1->execute();
                                        $total_rows = $query1->rowCount();
                                        $total_pages = ceil($total_rows / $no_of_records_per_page);

                                        $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblnotice.NoticeTitle, tblnotice.CreationDate, tblnotice.ID as nid 
                                                FROM tblnotice 
                                                JOIN tblclass ON tblclass.ID = tblnotice.ClassId 
                                                LIMIT $offset, $no_of_records_per_page";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        $cnt = $offset + 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td><?php echo htmlentities($row->NoticeTitle); ?></td>
                                                    <td><?php echo htmlentities($row->ClassName); ?></td>
                                                    <td><?php echo htmlentities($row->Section); ?></td>
                                                    <td><?php echo htmlentities($row->CreationDate); ?></td>
                                                    <td>
                                                        <a href="edit_notice.php?editid=<?php echo htmlentities($row->nid); ?>" class="btn btn-custom">View</a>
                                                        <a href="manage_notice.php?delid=<?php echo htmlentities($row->nid); ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger">Delete</a>
                                                    </td>
                                                </tr>
                                        <?php
                                                $cnt++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center mt-4">
                                <a href="?pageno=1" class="btn btn-custom <?php echo ($pageno <= 1) ? 'disabled' : ''; ?>">First</a>
                                <a href="<?php echo ($pageno <= 1) ? '#' : '?pageno=' . ($pageno - 1); ?>" class="btn btn-custom <?php echo ($pageno <= 1) ? 'disabled' : ''; ?>">Prev</a>
                                <a href="<?php echo ($pageno >= $total_pages) ? '#' : '?pageno=' . ($pageno + 1); ?>" class="btn btn-custom <?php echo ($pageno >= $total_pages) ? 'disabled' : ''; ?>">Next</a>
                                <a href="?pageno=<?php echo $total_pages; ?>" class="btn btn-custom <?php echo ($pageno >= $total_pages) ? 'disabled' : ''; ?>">Last</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
