<?php
// Database settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'pamz_db');

// Upload settings
$uploadDir = 'uploads/';
$allowTypes = array('jpg','png','jpeg','gif');

// Start session
if(!session_id()){
    session_start();
}

?>