<?php
// Include and initialize DB class
require_once 'DB.class.php';
$db = new DB();

$postData = $proData = array();

// Get session data
$sessData = !empty($_SESSION['sessData']) ? $_SESSION['sessData'] : '';

// Get status message from session
$statusMsg = !empty($sessData['status']['msg']) ? $sessData['status']['msg'] : '';
$statusMsgType = !empty($sessData['status']['type']) ? $sessData['status']['type'] : '';
unset($_SESSION['sessData']['status']);

// Get posted data from session
if (!empty($sessData['postData'])) {
    $postData = $sessData['postData'];
    unset($_SESSION['sessData']['postData']);
}

// Fetch data from the database
if (!empty($_GET['id'])) {
    $conditions = array(
        'where' => array('id' => $_GET['id']),
        'return_type' => 'single'
    );
    $proData = $db->getRows($conditions);
}

// Pre-filled data
$proData = !empty($postData) ? $postData : $proData;

// Define action
$actionLabel = !empty($_GET['id']) ? 'Edit' : 'Add';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $actionLabel; ?> Product</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4"><?php echo $actionLabel; ?> Product</h1>
    
    <!-- Display status message -->
    <?php if (!empty($statusMsg)) { ?>
        <div class="alert alert-<?php echo $statusMsgType; ?>"><?php echo $statusMsg; ?></div>
    <?php } ?>

    <div class="row">
        <div class="col-md-6">
            <a href="index.php" class="btn btn-secondary mb-3">&larr; Back</a>
            <form method="post" action="postAction.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter title" value="<?php echo !empty($proData['title']) ? $proData['title'] : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" placeholder="Enter description here..."><?php echo !empty($proData['description']) ? $proData['description'] : ''; ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Images</label>
                    <input type="file" name="image_files[]" class="form-control" accept="image/*" multiple>
                    <?php if (!empty($proData['images'])) { ?>
                        <div class="image-grid mt-3">
                            <?php foreach ($proData['images'] as $imageRow) { ?>
                                <div class="img-bx" id="imgbx_<?php echo $imageRow['id']; ?>">
                                    <img src="<?php echo $uploadDir . $imageRow['file_name']; ?>" width="120" alt="Image"/>
                                    <a href="javascript:void(0);" class="badge bg-danger" onclick="deleteImage(<?php echo $imageRow['id']; ?>)">Delete</a>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status1" value="1" <?php echo !empty($proData['status']) || !isset($proData['status']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="status1">Active</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status2" value="0" <?php echo isset($proData['status']) && empty($proData['status']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="status2">Inactive</label>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?php echo !empty($proData['id']) ? $proData['id'] : ''; ?>">
                <input type="submit" name="dataSubmit" class="btn btn-primary" value="Submit">
            </form>
        </div>
    </div>
</div>

<script>
function deleteImage(row_id) {
    if (row_id && confirm('Are you sure to delete image?')) {
        const imgElement = document.getElementById("imgbx_" + row_id);
        imgElement.style.opacity = "0.5";

        fetch("ajax_request.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ request_type: 'image_delete', row_id: row_id }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 1) {
                imgElement.remove();
            } else {
                alert(data.msg);
                imgElement.style.opacity = "1";
            }
        })
        .catch(console.error);
    }
}
</script>
</body>
</html>
