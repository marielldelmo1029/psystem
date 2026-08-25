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
        $sql = "DELETE FROM tblpublicnotice WHERE ID=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Data deleted');</script>";
        echo "<script>window.location.href = 'manage_public_notice.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Management System | Manage Public Announcement</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css" />
    <style>
        .main-panel {
            margin-top: -660px;
            margin-left: 20%;
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

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #bd2130;
        }
    </style>
</head>
<body>
<div class="container-scroller">
    <?php include_once('includes/header.php'); ?>
    <?php include_once('includes/sidebar.php'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-sm-flex align-items-center mb-4">
                                <h4 class="card-title mb-sm-0">Manage Public Notice</h4>
                            </div>
                            <div class="table-responsive border rounded p-1">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="font-weight-bold">No</th>
                                        <th class="font-weight-bold">Notice Title</th>
                                        <th class="font-weight-bold">Notice Date</th>
                                        <th class="font-weight-bold">Action</th>
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

                                    $ret = "SELECT ID FROM tblpublicnotice";
                                    $query1 = $dbh->prepare($ret);
                                    $query1->execute();
                                    $total_rows = $query1->rowCount();
                                    $total_pages = ceil($total_rows / $no_of_records_per_page);

                                    $sql = "SELECT * FROM tblpublicnotice LIMIT $offset, $no_of_records_per_page";
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
                                                <td><?php echo htmlentities($row->CreationDate); ?></td>
                                                <td>
                                                    <a href="edit_public_notice.php?editid=<?php echo htmlentities($row->ID); ?>"
                                                       class="btn btn-custom">View</a>
                                                    <a href="manage_public_notice.php?delid=<?php echo htmlentities($row->ID); ?>"
                                                       onclick="return confirm('Do you really want to Delete?');"
                                                       class="btn btn-danger">Delete</a>
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
                                <a href="<?php echo ($pageno <= 1) ? '#' : '?pageno=' . ($pageno - 1); ?>"
                                   class="btn btn-custom <?php echo ($pageno <= 1) ? 'disabled' : ''; ?>">Prev</a>
                                <a href="<?php echo ($pageno >= $total_pages) ? '#' : '?pageno=' . ($pageno + 1); ?>"
                                   class="btn btn-custom <?php echo ($pageno >= $total_pages) ? 'disabled' : ''; ?>">Next</a>
                                <a href="?pageno=<?php echo $total_pages; ?>"
                                   class="btn btn-custom <?php echo ($pageno >= $total_pages) ? 'disabled' : ''; ?>">Last</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
</div>
<script src="js/bootstrap.bundle.min.js"></script>

<script src="./js/dashboard.js"></script>
</body>
</html>
<?php } ?>
