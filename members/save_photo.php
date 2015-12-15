<?php
require_once "member_profile2.php"; 
/* file to save photo into 'images' folder after user press 'Take a Snapshot' button */

$file_id = 0;  // first assign 0 to new photo, then id will be changed after 'Submit' in 'display_members.php' file
               // or in 'member_profile2.php'
    
$file_path = "images/" . $file_id . ".jpg";
$file = file_put_contents($file_path, file_get_contents('php://input'));  // 'php://input' receive data from 'webcam.js' (raw data)
if(!$file){                                                               // 'php://input' allows you to read raw POST data
    die("Error: Failed to write data to file, check permissions");
}    




