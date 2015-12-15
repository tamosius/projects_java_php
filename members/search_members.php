<?php

require_once "connect_database.php";
require_once "sanitize_string.php";
include_once "calculate_everything.php";

/*------------ search request from 'table.js' file, find members by typing name in search box main window ------------------------------------*/
if(isset($_POST['search_text'])){
    $search_value = sanitize_string($_POST['search_text']);
    
    $query = "select id, first_name, last_name, membership_from,"
            . "membership_to, paid from members where first_name LIKE '$search_value%' or last_name LIKE '$search_value%' "
            . "order by case when membership_to is NULL then 1 else 0 end, membership_to";
    $result = $connection -> query($query);
    if(!$result){
        die("Failed to retrieve results from database!");
    }
    
    $rows_no = $result -> num_rows;
    
    for($i = 0; $i < $rows_no; $i++){
        
        $result -> data_seek($i);
        $row = $result -> fetch_array(MYSQLI_ASSOC);
        $id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $date_from = strtotime($row['membership_from']);
        $membership_from = strlen($date_from) !== 0 ? date('d-m-Y', $date_from) : '<p style="font-size:12px; color:#FF6262;">no membership</p>';
        $date_to =  strtotime($row['membership_to']);
        $days_left = strlen($date_to) ==! 0 ? calculate_days_left($row['membership_to']) : '';  // calulate how many days left for membership
                                                                                                // function in 'calculate_days.php' file
        $membership_to = strlen($date_to) !== 0 ? date('d-m-Y', $date_to) . '<p class="days_left">' . $days_left . '</p>' : '<p style="font-size:12px; color:#FF6262;">no membership</p>';
        $paid = strlen($row['paid']) !== 0 ? $row['paid'] : 0;
        echo <<< _END
        <tr class="row_data">
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
/*------------------------ SHOW ALL VISITED DATES OF MEMBER, CURRENTLY SHOWING IN 'last visited member' -----------------------------------------------------*/
else if(isset($_POST['last_visited_id'])){ 
    $last_visited_id = sanitize_string($_POST['last_visited_id']);
    
    $query = "select l.id, m.first_name, m.last_name, l.visited_date, l.visited_time from members m "
            . "join last_visited l on m.id = l.id "
            . "where l.id = '$last_visited_id' order by timestamp_date DESC";
    $result = $connection -> query($query);
    if(!$result){
        die("Failed to retrieve all visits of member from database!");
    }
    
    $rows_no = $result -> num_rows; // display as number of visits
    
    for($i = 0; $i < $rows_no; $i++){
        
        $result -> data_seek($i);
        $row = $result -> fetch_array(MYSQLI_ASSOC);
        $id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $full_name = $first_name . " " . $last_name;
        $date = date('d-m-Y', strtotime($row['visited_date']));
        //$day =  date('l', strtotime($row['visited_date']));
        $get_day = show_last_visited_date($row['visited_date']); // get day as 'today' or 'yesterday' or date from 'calculate_days.php' file
        $day = ($get_day !== 'today' && $get_day !== 'yesterday') ? date('l', strtotime($row['visited_date'])) : $get_day; // print 'today' or 'yesterday' or date
        $time =  date('H:i:s', strtotime($row['visited_time']));
        $date_day_time = '<div style="color: #00003D; width: 250px; float: left;">' . $date . ', <span style="color: #525E52;">' . $day . '</span></div>at <span style="color: #1d581d;">' . $time . '</span>';
        
        
        echo <<< _END
        <tr class="row_data_last_visited_updated">
            <input type="hidden" id="member_id" name="member_id" value="$id" />
            <input type="hidden" id="total_visits" value="$rows_no" />
            <input type="hidden" id="last_visited_member_name" value="$full_name" />
            <td class="last_visited_date_time">$date_day_time</td>
        </tr>
_END;
    
      /*<td class="from_data">$membership_from</td>
            <td class="to_data">$membership_to</td>
            <td class="paid_data">$paid</td> */                                                                                  
    }
    $result -> close();
    $connection -> close();
}
/*------------------------ TO RETRIEVE TODAYS VISITS, GET NAMES ETC.-------------------------------------------------------- */
else if(isset($_POST['today_visits'])){
    $today = date('Y-m-d');
    
    $query = "select l.id, m.first_name, m.last_name, l.visited_date, l.visited_time from members m "
            . "join last_visited l on m.id = l.id "
            . "where l.visited_date = '$today' order by timestamp_date DESC";
    $result = $connection -> query($query);
    if(!$result){
        die("Failed to retrieve all visits of member from database!");
    }
    
    $rows_no = $result -> num_rows;
    
    for($i = 0; $i < $rows_no; $i++){
        
        $result -> data_seek($i);
        $row = $result -> fetch_array(MYSQLI_ASSOC);
        $id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $full_name = $first_name . " " . $last_name;
        $date = date('d-m-Y', strtotime($row['visited_date']));
        $day =  date('l', strtotime($row['visited_date']));
        $date_day = $date . ", " . $day;
        $time =  date('H:i:s', strtotime($row['visited_time']));
        $full_name_time = '<div style="color: #00003D; width: 300px; float: left;">' . $full_name . '</div>at <span style="color: #1d581d;">' . $time . '</span>';
        
        
        echo <<< _END
        <tr class="row_data">
            <input type="hidden" id="member_id" name="member_id" value="$id" />
            <input type="hidden" id="number_of_visitors" value="$rows_no" />
            <input type="hidden" id="today_date_day" value="$date_day" />
            <td class="today_visited_name">$full_name_time</td>
        </tr>
_END;
    
      /*<td class="from_data">$membership_from</td>
            <td class="to_data">$membership_to</td>
            <td class="paid_data">$paid</td> */                                                                                  
    }
    $result -> close();
    $connection -> close();
}
/*---------------- GET ALL MEMBERS WHO HAS UPDATED/EXTENDED THEIR MEMBERSHIP STATUS -------------------------------------------------------------------- */
else if(isset($_POST['all_extended_memberships'])){
    $query = "select m.id, m.first_name, m.last_name, m.membership_from, m.membership_to, m.paid, l.updated_date, l.updated_time "
            . "from members m join last_updated l on m.id = l.id "
            . "order by timestamp_date DESC";
    $result = $connection -> query($query);
    if(!$result){
        die("Failed to retrieve all members who has updated/extended their membership status!");
    }
    
    $rows_no = $result -> num_rows;
    
    for($i = 0; $i < $rows_no; $i++){
        
        $result -> data_seek($i);
        $row = $result -> fetch_array(MYSQLI_ASSOC);
        
        $id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $full_name = $first_name . " " . $last_name;
        $date = date('d-m-Y', strtotime($row['updated_date']));
        $get_day = show_last_visited_date($row['updated_date']); // get day as 'today' or 'yesterday' or date from 'calculate_days.php' file
        $day = ($get_day !== 'today' && $get_day !== 'yesterday') ? date('l', strtotime($row['updated_date'])) : $get_day; // print 'today' or 'yesterday' or date
        $time =  date('H:i:s', strtotime($row['updated_time']));
        $date_day_time = "<div style='color: #00003D; width: 250px; float: left;'>" . $date . ", <span style='color: #525E52;'>" . $day . "</span></div>at <span style='color: #1d581d;'>" . $time . "</span>";
        $from = $row['membership_from'];  // get membership_from date from database
        $to = $row['membership_to']; // get membership_to date from database
        $membership_from = strlen(strtotime($from)) !== 0 ? date('d-m-Y', strtotime($from)) : "<p style='font-size:12px; color:#FF6262;'>no membership</p>";
        $membership_to = strlen(strtotime($to)) !== 0 ? date('d-m-Y', strtotime($to)) . "<p class='days_left'>" . calculate_days_left($to) . "</p>" : "<p style='font-size:12px; color:#FF6262;'>no membership</p>";
        $paid = $row['paid'];
        
        //$lowest_update_date = date('d-m-Y', strtotime($row['min_date']));
        //<input type="hidden" id="lowest_update_date" value="$lowest_update_date" />
        echo <<< _END
        <tr class="row_data">
            <input type="hidden" id="member_id" name="member_id" value="$id" />
            <input type="hidden" id="number_of_members" value="$rows_no" />
            <td class="first_name_data">$full_name</td>
            <td class="last_name_data">$date_day_time</td>
            <td class="from_data">$membership_from</td>
            <td class="to_data">$membership_to</td>
            <td class="paid_data">$paid</td>
        </tr>
_END;
    
      /*<td class="from_data">$membership_from</td>
            <td class="to_data">$membership_to</td>
            <td class="paid_data">$paid</td> */                                                                                  
    }
    $result -> close();
    $connection -> close();
}
/*-------------- SHOW ALL MEMBERS VISITED RECENTLY FOR SELECTED NUMBER OF WEEKS ----------------------------------------------------------------------- */
else if(isset($_POST['last_visited_members'])){
    $todays_date = date('d-m-Y');
    $days_period = sanitize_string($_POST['last_visited_members']); // number of days backwards for whick calculate attendance of members
    $date_from = date('d-m-Y', strtotime($todays_date . " -" . $days_period . " days")); // subtract days from todays date 
    
    
    $query = "select m.id, m.first_name, m.last_name, m.membership_from, m.membership_to, m.paid, l.visited_date, l.visited_time "
            . "from members m "
            . "join last_visited l on m.id = l.id where l.visited_date <= curdate() and visited_date >= subdate(curdate(), interval "
            . $days_period . " day) order by l.timestamp_date DESC";
    $result = $connection -> query($query);
    if(!$result){
        die("Failed to retrieve all members who visited the gym!");
    }
    
    $rows_no = $result -> num_rows;
    
    for($i = 0; $i < $rows_no; $i++){
        
        $result -> data_seek($i);
        $row = $result -> fetch_array(MYSQLI_ASSOC);
        
        $id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $full_name = $first_name . " " . $last_name;
        $date = date('d-m-Y', strtotime($row['visited_date']));
        $get_day = show_last_visited_date($row['visited_date']); // get day as 'today' or 'yesterday' or date from 'calculate_days.php' file
        $day = ($get_day !== 'today' && $get_day !== 'yesterday') ? date('l', strtotime($row['visited_date'])) : $get_day; // print 'today' or 'yesterday' or date
        $time =  date('H:i:s', strtotime($row['visited_time']));
        $date_day_time = "<div style='color: #00003D; width: 250px; float: left;'>" . $date . ", <span style='color: #525E52;'>" . $day . "</span></div>at <span style='color: #1d581d;'>" . $time . "</span>";
        $from = $row['membership_from'];  // get membership_from date from database
        $to = $row['membership_to']; // get membership_to date from database
        $membership_from = strlen(strtotime($from)) !== 0 ? date('d-m-Y', strtotime($from)) : "<p style='font-size:12px; color:#FF6262;'>no membership</p>";
        $membership_to = strlen(strtotime($to)) !== 0 ? date('d-m-Y', strtotime($to)) . "<p class='days_left'>" . calculate_days_left($to) . "</p>" : "<p style='font-size:12px; color:#FF6262;'>no membership</p>";
        $paid = $row['paid'];
        
        
        echo <<< _END
        <tr class="row_data">
            <input type="hidden" id="member_id" name="member_id" value="$id" />
            <input type="hidden" id="number_of_visitors" value="$rows_no" />
            <input type="hidden" id="date_from" value="$date_from" />
            <td class="first_name_data">$full_name</td>
            <td class="last_name_data">$date_day_time</td>
            <td class="from_data">$membership_from</td>
            <td class="to_data">$membership_to</td>
            <td class="paid_data">$paid</td>
        </tr>
_END;
    
      /*<td class="from_data">$membership_from</td>
            <td class="to_data">$membership_to</td>
            <td class="paid_data">$paid</td> */                                                                                  
    }
    $result -> close();
    $connection -> close();
}
/*---------------- SHOW ALL MEMBERS WITH 'no membership' STATUS ------------------------------------------------------------------------------------- */
else if(isset($_POST['no_membership_status'])){
    $query = "select id, first_name, last_name, membership_to from members where membership_to < curdate() or membership_to is NULL";
    
    $result = $connection -> query($query);
    if(!$result){
        die("Error: Could not retrieve members with expired membership status.");
    }
    
    $rows_no = $result -> num_rows;
    
    $total_members = calculate_total_members();
    
    for($i = 0; $i < $rows_no; $i++){
        $result -> data_seek($i);
        $row = $result -> fetch_array(MYSQLI_ASSOC);
        
        $id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $full_name = $first_name . " " . $last_name;
        $membership_to = $row['membership_to'];
        $membership_status = strlen(strtotime($membership_to)) !== 0 ? "<span style='font-size: 14px;'>membership expired on </span>'<span style='font-size: 16px; color: #1d581d;'>" 
                . date('d-m-Y', strtotime($membership_to)) . "</span>'" : "<span style='color: #FF6262; font-size: 14px;'>no membership</span>";
        
        $full_name_membership_status = "<div style='color: #00003D; width: 300px; float: left;'>" . $full_name . "</div>" . $membership_status;
        
        
        echo <<< _END
        <tr class="row_data">
            <input type="hidden" id="member_id" name="member_id" value="$id" />
            <input type="hidden" id="number_of_members" value="$rows_no" />
            <input type="hidden" id="total_members" value="$total_members" />
            <td class="today_visited_name">$full_name_membership_status</td>
        </tr>
_END;
    }
}

/*------------- CALCULATE ATTENDANCE BY DAYS (MOST VISITED DAYS) -------------------------------------------------------------------------------------- */
else if(isset($_POST['most_visited_days'])){
    $todays_date = date('d-m-Y');
    $days_period = sanitize_string($_POST['most_visited_days']); // number of days backwards for whick calculate attendance of members
    $date_from = date('d-m-Y', strtotime($todays_date . " -" . $days_period . " days")); // subtract days from todays date 
    
    $day['Monday'] = 0;
    $day['Tuesday'] = 0;
    $day['Wednesday'] = 0;
    $day['Thursday'] = 0;
    $day['Friday'] = 0;
    $day['Saturday'] = 0;
    $day['Sunday'] = 0;
    
    $query = "select visited_date from last_visited"
            . " where visited_date < curdate() and visited_date >= subdate(curdate(), interval " . $days_period . " day)";
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
    
    foreach($day as $day_of_week => $total_visitors){
        
        if($rows != 0){
            
            $percentage = $total_visitors / $rows * 100;  // calculate percentage of attendace by day of the week
            
        }else{
            
            $percentage = 0;
        }
        $day_visitors_percentage = "<div style='color: #00003D; width: 180px; float: left;'>" . $day_of_week . "</div><span style='color: #525E52; float: left; width: 190px;'>- " . $total_visitors . " " . 
                ($total_visitors == 1 ? 'visitor' : 'visitors') . "</span><span style='color: #1d581d;'>(" . number_format($percentage, 2) . " %)</span>";
        
        echo <<< _END
        <tr class="row_data_last_visited_updated">
            <input type="hidden" id="date_from" value="$date_from" />
            <td class="first_name_data">$day_visitors_percentage</td>
        </tr>
_END;
    }
    $result -> close();
    $connection -> close();
}

