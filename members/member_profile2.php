<?php

require_once "connect_database.php";
require_once "sanitize_string.php";

/*------------ GET MEMBER PROFILE DETAILS ON DBLCLICK ON ROW, BOTTOM PANEL, OR 'cancel update' BUTTON ------------------------------------------------ */
if(isset($_POST['member_profile_id'])){ 
    $member_profile_id = sanitize_string($_POST['member_profile_id']);
    /*$_SESSION['member_id'] = $member_profile_id;  // assign session variable and make available for 'save_photo.php' file, that we will need to assign
                                   // for photo id*/
                                   
    $query = "select first_name, last_name, address, ph_number, date_of_birth, email, membership_from, membership_to,"
            . " paid, date_created from members where id = " . $member_profile_id;
    $result = $connection -> query($query);
    if(!$result){
        die("Failed to retrieve members data");
    }
    
    $result -> data_seek(0);
    $row = $result -> fetch_array(MYSQLI_ASSOC);
    
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $address = strlen($row['address']) !== 0 ? $row['address'] : 'N / A';
    $ph_number = strlen($row['ph_number']) !== 0 ? $row['ph_number'] : 'N / A';
    $date_of_birth = strtotime($row['date_of_birth']);
    $date_of_birth = strlen($date_of_birth) !== 0 ? date('d-m-Y', $date_of_birth) : 'N / A';
    $email = strlen($row['email']) !== 0 ? $row['email'] : 'N / A';
    $date_from = strtotime($row['membership_from']);
    $membership_from = strlen($date_from) !== 0 ? date('d-m-Y', $date_from) : 'no membership';
    $date_to =  strtotime($row['membership_to']);
    $membership_to = strlen($date_to) !== 0 ? date('d-m-Y', $date_to) : 'no membership';
    $paid = strlen($row['paid']) !== 0 ? $row['paid'] : 0;
    $date_joined = strtotime($row['date_created']);
    $date_joined = strlen($date_joined) !== 0 ? date('d-m-Y', $date_joined) : 'N / A';
    
    $member_profile_photo_path = "images/no_photo.png";  // photo path to display in profile
    
    if(file_exists("images/" . $member_profile_id . ".jpg")){
        $member_profile_photo_path = "images/" . $member_profile_id . ".jpg";
    }
    
    $result -> close();
    $connection -> close();
    
    echo <<< _END
    <data>
        <member_id>$member_profile_id</member_id>
        <first_name>$first_name</first_name>
        <last_name>$last_name</last_name>
        <address>$address</address>
        <ph_number>$ph_number</ph_number>
        <date_of_birth>$date_of_birth</date_of_birth>
        <email>$email</email>
        <membership_from>$membership_from</membership_from>
        <membership_to>$membership_to</membership_to>
        <paid>$paid</paid>
        <date_joined>$date_joined</date_joined>
        <photo_path>$member_profile_photo_path  </photo_path>
    </data>
_END;
}
