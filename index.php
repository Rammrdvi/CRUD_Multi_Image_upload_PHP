<?php
// Include and initialize DB class
require_once 'DB.class.php';
$db = new DB();

// Fetch existing records from database
$products = $db->getRows();

// Get session data
$sessData = !empty($_SESSION['sessData']) ? $_SESSION['sessData'] : '';

// Get status message from session
$statusMsg = !empty($sessData['status']['msg']) ? $sessData['status']['msg'] : '';
$statusMsgType = !empty($sessData['status']['type']) ? $sessData['status']['type'] : '';
unset($_SESSION['sessData']['status']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operations with Multiple Images in PHP</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">CRUD Operations with Multiple Images</h1>

    <!-- Display status message Start-->
    <?php if (!empty($statusMsg)) { ?>
        <div class="alert alert-<?php echo $statusMsgType; ?>"><?php echo $statusMsg; ?></div>
    <?php } ?>
<!-- Display status message End-->

    <div class="row mb-4">
        <div class="col-md-6">
            <h5>Products</h5>
        </div>
        <div class="col-md-6 text-end">
            <a href="addEdit.php" class="btn btn-success">New Product</a>
        </div>
    </div>

    <!-- List all the products start-->
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th width="2%">#</th>
                <th width="10%">Image</th>
                <th width="20%">Title</th>
                <th width="30%">Description</th>
                <th width="15%">Created</th>
                <th width="8%">Status</th>
                <th width="15%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)) {
                foreach ($products as $row) {
                    $statusLink = ($row['status'] == 1) ? 'postAction.php?action_type=block&id=' . $row['id'] : 'postAction.php?action_type=unblock&id=' . $row['id'];
                    $statusTooltip = ($row['status'] == 1) ? 'Click to Inactive' : 'Click to Active';
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td class="text-center">
                    <?php if (!empty($row['file_name'])) { ?>
                        <img src="<?php echo $uploadDir . $row['file_name']; ?>" width="120" alt="<?php echo $row['title']; ?>"/>
                    <?php } ?>
                </td>
                <td><?php echo $row['title']; ?></td>
                <td>
                    <?php
                    $description = strip_tags($row['description']);
                    echo (strlen($description) > 140) ? substr($description, 0, 140) . '...' : $description;
                    ?>
                </td>
                <td><?php echo $row['created']; ?></td>
                <td>
                    <a href="<?php echo $statusLink; ?>" title="<?php echo $statusTooltip; ?>">
                        <span class="badge bg-<?php echo ($row['status'] == 1) ? 'success' : 'danger'; ?>">
                            <?php echo ($row['status'] == 1) ? 'Active' : 'Inactive'; ?>
                        </span>
                    </a>
                </td>
                <td>
                    <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View</a>
                    <a href="addEdit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="postAction.php?action_type=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure to delete this record?');">Delete</a>
                </td>
            </tr>
            <?php } } else { ?>
            <tr><td colspan="7" class="text-center">No record(s) found...</td></tr>
            <?php } ?>
        </tbody>
    </table>

     <!-- List all the products End-->
</div>
</body>
</html>
