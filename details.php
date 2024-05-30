<?php
// Include and initialize DB class
require_once 'DB.class.php';
$db = new DB();

// If record ID is available in the URL
if(!empty($_GET['id'])){
    // Fetch data from the database
    $conditions['where'] = array(
        'id' => $_GET['id']
    );
    $conditions['return_type'] = 'single';
    $proData = $db->getRows($conditions);
}else{
    // Redirect to listing page
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<title>CRUD Operations with Multiple Images in PHP by Pamz</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap library -->
<link rel="stylesheet" href="css/bootstrap.min.css">

<!-- Stylesheet file -->
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>CRUD Operations with Multiple Images</h1>
	<hr>

    <div class="row align-items-start col-md-6">
        <div class="col col-md-12">
            <h5 class="float-start">Product Details</h5>
            
            <div class="float-end">
                <a href="index.php" class="btn btn-secondary"><-- Back</a>
            </div>
        </div>
        
        <div class="col col-md-12">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <p><?php echo !empty($proData['title'])?$proData['title']:''; ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <p><?php echo !empty($proData['description'])?$proData['description']:''; ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label">Images</label>
                <?php if(!empty($proData['images'])){ ?>
                    <div class="image-grid">
                    <?php foreach($proData['images'] as $imageRow){ ?>
                        <div class="img-bx" id="imgbx_<?php echo $imageRow['id']; ?>">
                            <img src="<?php echo $uploadDir.$imageRow['file_name']; ?>" width="120"/>
                            <a href="javascript:void(0);" class="badge text-bg-danger" onclick="deleteImage(<?php echo $imageRow['id']; ?>)">delete</a>
                        </div>
                    <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <p><?php echo $proData['status'] == 1?'<span class="badge text-bg-success">Active</span>':'<span class="badge text-bg-danger">Inactive</span>'; ?></p>
            </div>
        </div>
    </div>
</div>

<script>
function deleteImage(row_id) {
	if(row_id && confirm('Are you sure to delete image?')){
        const img_element = document.getElementById("imgbx_"+row_id);

        img_element.setAttribute("style", "opacity:0.5;");

		fetch("ajax_request.php", {
			method: "POST",
			headers: { "Content-Type": "application/json" },
			body: JSON.stringify({ request_type:'image_delete', row_id: row_id }),
		})
		.then(response => response.json())
		.then(data => {
			if (data.status == 1) {
				img_element.remove();
			} else {
				alert(data.msg);
			}
            img_element.setAttribute("style", "opacity:1;");
		})
		.catch(console.error);
	}
}
</script>
</body>
</html>