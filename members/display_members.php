<?php


require_once "connect_database.php";
require_once "sanitize_string.php";
require_once "calculate_everything.php";
/*------------------- CHECK THIS!!!! ------------------------------------------------------------------------------------------------ */
if(isset($_GET['id_member'])){  // if new member has been added to the list, get the name and print to the screen
    $first_name = $_GET['first_name'];  // values from 'convert_to_pdf.php'
    $last_name = $_GET['last_name'];
    $added_profile = $first_name . " " . $last_name;
}

/*--------------------------- UPDATE MEMBER PROFILE --------------------------------------------------------------------------------- */
if(isset($_POST['update_member_submit'])){
    $today_date = date('d-m-Y');  // get today's date
    
    $membership_to_before_update = sanitize_string($_POST['membership_to_before_update']); // get membership_to date before update, value in hidden 'input' in 'index.php' 
    
    $query = $connection -> prepare("update members set first_name = ?, last_name = ?, address = ?, ph_number = ?, date_of_birth = ?,"
            . " email = ?, membership_from = ?, membership_to = ?, paid = ?, date_created = ? where id = ?");
            
    $query -> bind_param("ssssssssssi", $first_name, $last_name, $address, $ph_number, $date_of_birth, $email, $membership_from_insert,
            $membership_to_insert, $paid, $date_joined, $id);
    
    $id = sanitize_string($_POST['member_id']);
    $first_name = ucwords(strtolower(sanitize_string($_POST['first_name'])));  // convert first letter to upper case 'ucwords()' function
    $last_name = ucwords(strtolower(sanitize_string($_POST['last_name'])));    // convert first letter to upper case
    $address = sanitize_string($_POST['address']);
    $ph_number = sanitize_string($_POST['ph_number']);
    $date_of_birth = sanitize_string($_POST['date_of_birth']);
    $date_of_birth = strlen(strtotime($date_of_birth)) !== 0 ? date('Y-m-d', strtotime($date_of_birth)) : NULL;
    $email = sanitize_string($_POST['email']);
    $membership_from = sanitize_string($_POST['from']);
    $membership_from_insert = strlen(strtotime($membership_from)) !== 0 ? date('Y-m-d', strtotime($membership_from)) : NULL;
    $membership_from = strlen(strtotime($membership_from)) !== 0 ? date('Y-m-d', strtotime($membership_from)) : '<p style="font-size:12px; color:#FF6262;">no membership</p>';
    $membership_to_date = sanitize_string($_POST['to']);
    $membership_to_insert = strlen(strtotime($membership_to_date)) !== 0 ? date('Y-m-d', strtotime($membership_to_date)) : NULL;
                                                                                           // function in 'calculate_days.php' file
    $membership_to = strlen(strtotime($membership_to_date)) !== 0 ? date('Y-m-d', strtotime($membership_to_date)) : '<p style="font-size:12px; color:#FF6262;padding-bottom: 6px;">no membership</p>';
    $days_left = calculate_days_left($membership_to_date);
    // if membership valid, print on the screen how many days left
    $membership_to_print = strlen(strtotime($membership_to)) !== 0 ? date('d-m-Y',strtotime($membership_to)) . '<p class="days_left">' . calculate_days_left($membership_to) . '</p>': $membership_to;
    $paid = sanitize_string($_POST['paid']);
    $paid = strlen($paid) !== 0 ? $paid : 0;
    $date_joined = sanitize_string($_POST['date_joined']);
    $date_joined = strlen(strtotime($date_joined)) !== 0 ? date('Y-m-d', strtotime($date_joined)) : NULL;
    
    $query -> execute();
    
    $photo_path = 'images/no_photo.png';
    if(file_exists('images/' . $id . '.jpg')){                      
        $photo_path = 'images/' . $id . '.jpg';
    }
    
    $updated_membership = 'false'; // false if user does not extending membership
    
    if(strtotime($membership_to_before_update) < strtotime($membership_to) && strtotime($today_date) < strtotime($membership_to)){
        // insert update date into 'last_updated' table if user extends his membership
        $query_for_update = "insert into last_updated (id, updated_date, updated_time, timestamp_date) "
                . "values('$id', curdate(), curtime(), now())"; 
        $result_for_update = $connection -> query($query_for_update);
        if(!$result_for_update){
            die("Error! Could not insert values for updating a membership status");
        }else{
            $updated_membership = 'true';
        }
    }
    
    $full_name = $first_name . " " . $last_name;
    
    // get total number of members, calculate how many members with 'no membership' status and also calculate procentage
    $total_members = calculate_total_members();
    $total_no_membership_members = calculate_no_membership_members();
    $percentage_no_membership_members = round($total_no_membership_members / $total_members * 100);
    
    echo <<< _END
        <tr class="row_data">
            <input type="hidden" id="member_id" name="member_id" value="$id" />
            <input type="hidden" id="full_name" value="$full_name" />
            <input type="hidden" id="updated_membership" value="$updated_membership" />
            <input type="hidden" id="days_left" value="$days_left" />
            <input type="hidden" id="paid_data" value="$paid" />
            <input type="hidden" id="today_date" value="$today_date" />
            <input type="hidden" id="membership_to" value="$membership_to_date" />
            <input type="hidden" id="photo_path" value="$photo_path" />
            <input type="hidden" id="message" value="profile succesfully updated!!" />
            <input type="hidden" id="total_members" value="$total_members" />
            <input type="hidden" id="no_membership_members" value="$total_no_membership_members" />
            <input type="hidden" id="percentage_no_membership_members" value="$percentage_no_membership_members" />
            <td class="first_name_data"><span id="check_in">Check-in</span>$first_name</td>
            <td class="last_name_data">$last_name</td>
            <td class="from_data">$membership_from</td>
            <td class="to_data">$membership_to_print</td>
            <td class="paid_data">$paid</td>
            
        </tr>      
_END;
}
/*----------------------- ADD NEW MEMBER.  REQUEST FROM 'add_update_validation.js' ------------------------------------------------------------------------------------------------ */
else if(isset($_POST['add_member_submit'])){
    $today_date = date('d-m-Y');
    
    $query = $connection -> prepare("insert into members(first_name, last_name, address, ph_number, date_of_birth, email, membership_from, membership_to,"
            . " paid, date_created) values(?, ?, ?, ?, ?, ?, ?, ?, ?, now())");
    $query -> bind_param("sssssssss", $first_name, $last_name, $address, $ph_number, $date_of_birth, $email, $membership_from_insert,
            $membership_to_insert, $paid);
    
    
    $first_name = ucwords(strtolower(sanitize_string($_POST['first_name'])));  // convert first letter to upper case, 'ucwords()' function,
    $last_name = ucwords(strtolower(sanitize_string($_POST['last_name'])));    // convert first letter to upper case        
    $address = sanitize_string($_POST['address']);
    $ph_number = sanitize_string($_POST['ph_number']);
    $date_of_birth = sanitize_string($_POST['date_of_birth']);  // 'strtotime' validates date format, if invalid returns empty string
    $date_of_birth = strlen(strtotime($date_of_birth)) !== 0 ? date('Y-m-d', strtotime($date_of_birth)) : NULL;
    $email = sanitize_string($_POST['email']);
    $membership_from = sanitize_string($_POST['from']);
    $membership_from_insert = strlen(strtotime($membership_from)) !== 0 ? date('Y-m-d', strtotime($membership_from)) : NULL;
    $membership_from = strlen(strtotime($membership_from)) !== 0 ? date('Y-m-d', strtotime($membership_from)) : '<p style="font-size:12px; color:#FF6262;">no membership</p>';
    $membership_to_date = sanitize_string($_POST['to']);
    $membership_to_insert = strlen(strtotime($membership_to_date)) !== 0 ? date('Y-m-d', strtotime($membership_to_date)) : NULL;
                                                                                           // function in 'calculate_days.php' file
    $membership_to = strlen(strtotime($membership_to_date)) !== 0 ? date('Y-m-d', strtotime($membership_to_date)) : '<p style="font-size:12px; color:#FF6262;padding-bottom: 6px;">no membership</p>';
    $days_left = calculate_days_left($membership_to_date);
    // if membership valid, print on the screen how many days left
    $membership_to_print = strlen(strtotime($membership_to)) !== 0 ? date('d-m-Y',strtotime($membership_to)) . '<p class="days_left">' . calculate_days_left($membership_to) . '</p>': $membership_to;
    $paid = sanitize_string($_POST['paid']);
    $paid = strlen($paid) !== 0 ? $paid : 0;
    
    $query -> execute();  // execute the query
    
    $full_name = $first_name . " " . $last_name;
    $photo_path = 'images/no_photo.png';
    $id = mysqli_insert_id($connection);  // get id for last inserted profile, so we can use that id
                                         // to name photo image.jpg and also pdf file with the barcode
    if(file_exists('images/0.jpg')){  // if made first photo, it always has id = 0, then we rename it after new members profile
                                      // has been inserted into database and has been given new id
        $photo_path = 'images/' . $id . '.jpg';
        rename('images/0.jpg', $photo_path);  // rename photo taken with the new profile id created
    }
    
    $updated_membership = 'false'; // false if user does not extending membership
    
    if(strtotime($today_date) < strtotime($membership_to)){
        // insert update date into 'last_updated' table if user extends his membership
        $query_for_update = "insert into last_updated (id, updated_date, updated_time, timestamp_date) "
                . "values('$id', curdate(), curtime(), now())"; 
        $result_for_update = $connection -> query($query_for_update);
        if(!$result_for_update){
            die("Error! Could not insert values for updating a membership status");
        }else{
            $updated_membership = 'true';
        }
    }
    // get total number of members, calculate how many members with 'no membership' status and also calculate procentage
    $total_members = calculate_total_members();
    $total_no_membership_members = calculate_no_membership_members();
    $percentage_no_membership_members = round($total_no_membership_members / $total_members * 100);
    
    // create first barcode image.png file and assign new members id
    //header('Location: php_barcode_generator/create_barcode_image.php?id_member=' . $id . '&first_name=' . $first_name . '&last_name=' . $last_name);
    
    
    echo <<< _END
        <tr class="row_data">
            <input type="hidden" id="member_id" name="member_id" value="$id" />
            <input type="hidden" id="full_name" value="$full_name" />
            <input type="hidden" id="updated_membership" value="$updated_membership" />
            <input type="hidden" id="days_left" value="$days_left" />
            <input type="hidden" id="paid_data" value="$paid" />
            <input type="hidden" id="today_date" value="$today_date" />
            <input type="hidden" id="membership_to" value="$membership_to_date" />
            <input type="hidden" id="photo_path" value="$photo_path" />
            <input type="hidden" id="message" value="profile succesfully added to the list!!" />
            <input type="hidden" id="total_members" value="$total_members" />
            <input type="hidden" id="no_membership_members" value="$total_no_membership_members" />
            <input type="hidden" id="percentage_no_membership_members" value="$percentage_no_membership_members" />
            <td class="first_name_data"><span id="check_in">Check-in</span>$first_name</td>
            <td class="last_name_data">$last_name</td>
            <td class="from_data">$membership_from</td>
            <td class="to_data">$membership_to_print</td>
            <td class="paid_data">$paid</td>
        </tr>
_END;
    
/*------------- DELETE MEMBER PROFILE AND ALL VISITED/UPDATED DATES FROM DATABASE -------------------------------------------------------------------- */    
}else if(isset($_POST['delete_member_id'])){
    $delete_member_id = sanitize_string($_POST['delete_member_id']);
    //$first_name = sanitize_string($_GET['delete_first_name']);
    //$last_name = sanitize_string($_GET['delete_last_name']);
    
    
    $query = "delete from members where id = '$delete_member_id'";
    $result = $connection -> query($query);
    
    if(!$result){
        die($connection -> error . "Could not delete member profile!!");
    }
    $deleted_profile = $first_name . " " . $last_name;
    
    if(file_exists('images/' . $delete_member_id . '.jpg')){  // delete profile image from 'images' folder
        unlink('images/' . $delete_member_id . '.jpg');
    }
    
    // get total number of members
    $query_total_members = "select count(*) as count from members";  // get total number of members
    $result_for_total_members = $connection -> query($query_total_members);
    if(!$result_for_total_members){
        die("Error: Could not retrieve total number of members");
    }
    $result_for_total_members -> data_seek(0);
    $row_for_total_members = $result_for_total_members -> fetch_array(MYSQLI_ASSOC);
    $total_members = $row_for_total_members['count'];
}
/*------------ IF USER PRESS 'BACK' BUTTON IN 'add_member' OR 'member_profile', DO NOT SAVE IMAGE, DELETE IT ------------------------------------------*/
if(isset($_POST['do_not_save_image'])){  
    if(file_exists('images/0.jpg')){
    unlink('images/0.jpg');       // delete photo if not assigned to particular profile, and user press 'Back' button
}
}  


//session_start();
//session_unset(); // unset session variable '$_SESSION['member_id']' in file 'member_profile2.php' if it has been set
/*----- DISPLAY MAIN WINDOW -----------------------------------------------------------------------------------------------------------------------*/
if(isset($_POST['action'])){
    display();  // call function 'display()' when user clicks 'Main' button, display main table with all members
}
/*------ FUNCTION TO DISPLAY MAIN WINDOW WITH ALL MEMBERS ----------------------------------------------------------------------------------------*/
function display(){
    global $connection;
    
    $query = "select id, first_name, last_name, membership_from, membership_to, paid  from members "
            . "order by case when membership_to is NULL then 1 else 0 end, membership_to";
    $result = $connection -> query($query);
    if(!$result){
        echo "Error while retrieving information from database";
    }

    $rows = $result -> num_rows;
    //$execute_rows = $rows > 50 ? 50 : $rows;
    $_SESSION['total_members'] = $rows;

    for($i = 0; $i < $rows; $i++){
        
        $result -> data_seek($i);
        $row = $result -> fetch_array(MYSQLI_ASSOC);
        $id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $date_from = strtotime($row['membership_from']);
        $membership_from = strlen($date_from) !== 0 ? date('d-m-Y', $date_from) : '<p style="font-size:12px; color:#FF6262;">no membership</p>';
        $date_to =  strtotime($row['membership_to']);
        $days_left = calculate_days_left($row['membership_to']);  // calulate how many days left for membership
                                                                                                // function in 'calculate_days.php' file
        $membership_to = strlen($date_to) !== 0 ? date('d-m-Y', $date_to) . '<p class="days_left">' . $days_left . '</p>' : '<p style="font-size:12px; color:#FF6262;">no membership</p>';
        $paid = strlen($row['paid']) !== 0 ? $row['paid'] : 0;
        echo <<< _END
        <tr class="row_data">
            <input type="hidden" id="total_members" name="member_id" value="$rows" />
            <input type="hidden" id="member_id" name="member_id" value="$id" />
            <td class="first_name_data"><span id="check_in">Check-in</span>$first_name</td>
            <td class="last_name_data">$last_name</td>
            <td class="from_data">$membership_from</td>
            <td class="to_data">$membership_to</td>
            <td class="paid_data">$paid</td>
        </tr>
_END;
        
    
    }
    $result -> close();
    $connection -> close();
}


                



