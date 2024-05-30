<?php
// Include and initialize DB class
require_once 'DB.class.php';
$db = new DB();

// Retrieve JSON from POST body
$jsonStr = file_get_contents('php://input'); 
$jsonObj = json_decode($jsonStr); 

if ($jsonObj->request_type === 'image_delete') { 
    $row_id = !empty($jsonObj->row_id) ? $jsonObj->row_id : '';

    if ($row_id) {
        // Fetch previous file name from database
        $prevData = $db->get_image_row($row_id);
        $file_name_prev = $prevData['file_name'];

        // Delete record from database and file from server
        if ($db->delete_images(['id' => $row_id])) {
            @unlink($uploadDir . $file_name_prev);
            $output = ['status' => 1, 'msg' => 'Deleted!'];
        } else {
            $output = ['status' => 0, 'msg' => 'Image deletion failed!'];
        }
    } else {
        $output = ['status' => 0, 'msg' => 'Invalid image ID!'];
    }

    // Return response
    echo json_encode($output);
}
?>