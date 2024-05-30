<?php
// Include and initialize DB class
require_once 'DB.class.php';
$db = new DB();

// Set default redirect url
$redirectURL = 'index.php';

$statusMsg = $error = $errorMsg_img = '';
$sessData = array();
$statusType = 'danger';

// If add/edit form is submitted
if(isset($_POST['dataSubmit'])){
	// Set redirect url
    $redirectURL = 'addEdit.php';

	// Store submitted data into session
    $sessData['postData'] = $_POST;

    // Get submitted input data
	$title = $_POST['title'];
	$description = $_POST['description'];
	$status_input = $_POST['status'];
	$image_files = $_FILES['image_files'];
	$id	= $_POST['id'];
    
    // Prepare data array for database insertion
    $proData = array(
        'title'  => $title,
		'description'  => $description,
		'status'  => $status_input
    );
    
    // Specify ID query string
    $idStr = !empty($id)?'?id='.$id:'';

	// Input fields validation
	$error = '';
    if(empty($title)){
        $error .= 'Please enter title.<br>';
    }
    
    // If the data is not empty
    if(empty($error)){
		if(!empty($id)){
			// Update data in the database
			$condition = array('id' => $id);
			$update = $db->update($proData, $condition);
			
			if($update){
				$product_id = $id;
			}
		}else{
			// Insert data in the database
			$insert = $db->insert($proData);
			
			if($insert){
				$product_id = $insert;
			}
		}

		if(!empty($product_id)){
			// Upload images
			$fileNames = array_filter($image_files['name']); 
			if(!empty($fileNames)){ 
				foreach($image_files['name'] as $key=>$val){ 
					// File upload path 
					$fileName = time().'_'.basename($image_files['name'][$key]); 
					$targetFilePath = $uploadDir . $fileName; 
					
					// Check whether file type is valid 
					$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
					if(in_array($fileType, $allowTypes)){ 
						// Upload file to the server
						if(move_uploaded_file($image_files["tmp_name"][$key], $targetFilePath)){ 
							// Insert image in the database
							$imgData = array( 
                                'product_id' => $product_id, 
                                'file_name' => $fileName 
                            ); 
							$insertImage = $db->insert_image($imgData);
						}else{ 
							$errorUpload .= $image_files['name'][$key].' | '; 
						} 
					}else{ 
						$errorUploadType .= $image_files['name'][$key].' | '; 
					} 
				} 
				
				// File upload error message
				$errorUpload = !empty($errorUpload)?'File upload error: '.trim($errorUpload, ' | '):''; 
				$errorUploadType = !empty($errorUploadType)?'File type error: '.trim($errorUploadType, ' | '):''; 
				$errorMsg = !empty($errorUpload) ? '<br>'.$errorUpload.'<br>'.$errorUploadType : (!empty($errorUploadType) ? '<br>'.$errorUploadType : '' );
				$errorMsg_img = !empty($errorMsg)?'<span>'.$errorMsg.'</span>':'';
			}

			$statusType = 'success';
			$statusMsg = 'Product data has been submitted successfully!'.$errorMsg_img;
			$sessData['postData'] = '';
			
			// Set redirect url
			$redirectURL = 'index.php';
		}else{
			$statusMsg = 'Something went wrong, please try again!';
			// Set redirect url
			$redirectURL .= $idStr;
		}
	}else{
		$statusMsg = 'Please fill all the required fields:<br>'.trim($error, '<br>');
	}
	
	// Status message
	$sessData['status']['type'] = $statusType;
    $sessData['status']['msg']  = $statusMsg;
}elseif(($_REQUEST['action_type'] == 'block') && !empty($_GET['id'])){
    // Update status in the database
	$data = array('status' => 0);
    $condition = array('id' => $_GET['id']);
    $update = $db->update($data, $condition);

    if($update){
        $statusType = 'success';
        $statusMsg  = 'Product status changed to Inactive successfully.';
    }else{
        $statusMsg  = 'Something went wrong, please try again!';
    }
	
	// Status message
	$sessData['status']['type'] = $statusType;
    $sessData['status']['msg']  = $statusMsg;
}elseif(($_REQUEST['action_type'] == 'unblock') && !empty($_GET['id'])){
    // Update status in the database
	$data = array('status' => 1);
    $condition = array('id' => $_GET['id']);

    $update = $db->update($data, $condition);
    if($update){
        $statusType = 'success';
        $statusMsg  = 'Product status changed to Active successfully.';
    }else{
        $statusMsg  = 'Something went wrong, please try again!';
    }
	
	// Status message
	$sessData['status']['type'] = $statusType;
    $sessData['status']['msg']  = $statusMsg;
}elseif(($_REQUEST['action_type'] == 'delete') && !empty($_GET['id'])){
	$product_id = $_GET['id'];

	// Fetch previous data from database
	$conditions['where'] = array(
		'id' => $product_id
	);
	$conditions['return_type'] = 'single';
	$prevData = $db->getRows($conditions);
				
    // Delete record from the database
    $condition = array('id' => $product_id);
    $delete = $db->delete($condition);

    if($delete){
		// Delete image records from the database
		$condition = array('product_id' => $product_id);
    	$deleteImages = $db->delete_images($condition);

		// Remove physical files from the server
		if(!empty($prevData['images'])){
			foreach($prevData['images'] as $row){
				@unlink($uploadDir.$row['file_name']);
			}
		}
		
        $statusType = 'success';
        $statusMsg  = 'Product data has been deleted successfully.';
    }else{
        $statusMsg  = 'Something went wrong, please try again!';
    }
	
	// Status message
	$sessData['status']['type'] = $statusType;
    $sessData['status']['msg']  = $statusMsg;
}

// Store status into the session
$_SESSION['sessData'] = $sessData;
	
// Redirect the user
header("Location: ".$redirectURL);
exit();
?>