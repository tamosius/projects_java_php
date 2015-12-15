<?php
require_once 'connect_database.php';
 
/* -------------------- CALCULATE HOW MANY DAYS LEFT FOR MEMBERSHIP ---------------------------------------------------------------------------------- */
function calculate_days_left($membership_to){
    if(strlen(strtotime($membership_to) == 0)){
        return '';
    }
    $today = date('Y-m-d');
    $membership_to_date = date_create($membership_to);
    $todays_date = date_create($today);
    $interval = date_diff($todays_date, $membership_to_date);
    $result = $interval -> format('%R%a');
    $result1 = $interval -> format('%a');
    
    if($result > 5){
        return "<strong style='color: #1d581d;'>(" . $result1 . " days left)</strong>";
    }else if($result <= 5 && $result > 1){
        return "<strong style='color: #A33E3E;'>(" . $result1 . " days left)</strong>";
    }else if($result == 1){
        return "<strong style='color: #A33E3E;'>(" . $result1 . " day left)</strong>";
    }else if($result == 0){
        return "<strong style='color: red;'>(Last day)</strong>";
    }else{
        return "<strong style='color: red;'>(Membership expired)</strong>";
    }
}

/* ----------------- RETURN 'today', 'yesterday' OF DATE ------------------------------------------------------------------------------------------- */
function show_last_visited_date($date){
    $today = date('Y-m-d');
    $last_visited_date = date_create($date);
    $todays_date = date_create($today);
    $interval = date_diff($todays_date, $last_visited_date);
    $result = $interval -> format('%R%a');
    
    if($result == 0){
        return 'today';
    }else if($result == -1){
        return 'yesterday';
    }else{
        return date('d-m-Y', strtotime($date));
    }
}

/* -------------------- GET MEMBERSHIP STATUS -------------------------------------------------------------------------------------------------------- */
function membership_status($membership_status){
    if(strlen(strtotime($membership_status) == 0)){
        return "<strong style='font-size:10px; color:#FF6262;'>no membership</strong>";
    }else{
        return date('d-m-Y', strtotime($membership_status));
    }
}

/* --------------- GET TOTAL NUMBER OF MEMBERS ------------------------------------------------------------------------------------------------------- */
function calculate_total_members(){
    global $connection;
    
    $query = "select count(*) as count from members";
    $result = $connection -> query($query);
    if(!$result){
        die("Error: Could not retrieve total number of members");
    }
    $result -> data_seek(0);
    $row = $result -> fetch_array(MYSQLI_ASSOC);
    $total_members = $row['count'];
    
    //$result -> close();
    //$connection -> close();
    
    return $total_members;
}

/* --------------- GET TOTAL MEMBERS WITH 'no membership' STATUS --------------------------------------------------------------------------------------- */
function calculate_no_membership_members(){
    global $connection;
    
    $query = "select count(*) as count from members where membership_to < curdate() or membership_to is NULL";
    $result = $connection -> query($query);
    if(!$result){
        die("Error: Could not retrieve members with 'no membership' status.");
    }
    $result -> data_seek(0);
    $row = $result -> fetch_array(MYSQLI_ASSOC);
    $total_no_membership_members = $row['count'];
    
    //$result -> close();
    //$connection -> close();
    
    return $total_no_membership_members;
}
/*------------- CALCULATE ATTENDANCE BY DAYS (MOST VISITED DAYS) -------------------------------------------------------------------------------------- */
/*function calculate_most_visited_days($days){
    global $connection;
    
    $day['Monday'] = 0;
    $day['Tuesday'] = 0;
    $day['Wednesday'] = 0;
    $day['Thursday'] = 0;
    $day['Friday'] = 0;
    $day['Saturday'] = 0;
    $day['Sunday'] = 0;
    
    $query = "select visited_date from last_visited where visited_date < curdate() and visited_date >= subdate(curdate(), interval " . $days . " day)";
    $result = $connection -> query($query);
    if(!$result){
        die("Error: Could not retrieve most visited days.");
    }
    $rows = $result -> num_rows;
    
    for($i = 0; $i < $rows; $i++){
        $result -> data_seek($i);
        $row = $result -> fetch_array(MYSQLI_ASSOC);
        $get_date = $row['visited_date'];
        $get_day = date('l', strtotime($get_date)); // get day of the week 
        $day[$get_day]++; // add up 1
    }
    arsort($day);  // sort associative arrays in descending order, according to the value
    
    //return $day; // return array of calculated days by attendance
    
    foreach($day as $day_of_week => $total_visitors){
        $percentage = $total_visitors / $rows * 100;  // calculate percentage of attendace by day of the week
        
        $day_visitors_percentage = '<div style="color: #00003D; width: 250px; float: left;">' . $day_of_week . '</div><span style="color: #525E52;">- ' . $total_visitors . '</span><span style="color: #1d581d;">(' . $percentage . ' %)</span>';
    
        echo <<< _END
        <tr class="row_data>
           <td>$day_visitors_percentage</td>
        </tr>
_END;
    }
}*/

//calculate_most_visited_days(4);