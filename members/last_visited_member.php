<?php
require_once 'connect_database.php';
require_once 'calculate_everything.php';
/* file to insert last visited member id into 'last_visited' table and retrieve details of that members from database */

if(isset($_GET['last_visited_id'])){
    $today_date = date('Y-m-d');  // get today's date for counting today visits
    
    $last_visited_id = $_GET['last_visited_id'];//filter_input($GET, 'last_visited_id');
    
    $query_for_last_visited = "insert into last_visited(id, visited_date, visited_time, timestamp_date) values('$last_visited_id', curdate(), curtime(), now())";
    // query to retrieve details for last visited member
    $query_for_last_visited2 = "select m.id, m.first_name, m.last_name, m.membership_to, l.visited_date, l.visited_time from members m "
            . "join last_visited l on m.id = l.id where l.timestamp_date = "
            . "(select max(timestamp_date) from last_visited)";
    $query_for_today_visits = "select count(*)as count from last_visited where visited_date = '$today_date'";
    
    $result_for_insert = $connection -> query($query_for_last_visited);
    $result_for_last_visited = $connection -> query($query_for_last_visited2);
    $result_for_today_visits = $connection -> query($query_for_today_visits);
    if(!$result_for_insert){
        die('Error: Could not insert last visited member');
    }
    if(!$result_for_last_visited){
        die("Error: Could not retrieve last visited member");
    }
    if(!$result_for_today_visits){
        die("Error: Could not retrieve the number of today visists");
    }
    
    $result_for_last_visited -> data_seek(0);
    $row_for_last_visited = $result_for_last_visited -> fetch_array(MYSQLI_ASSOC);
    
    $id_for_last_visited = $row_for_last_visited['id'];
    $last_visited_first_name = $row_for_last_visited['first_name']; 
    $last_visited_last_name = $row_for_last_visited['last_name'];
                         // call function 'show_last_visited_date()' in 'calculate_days.php'. 
    $date_last_visited = show_last_visited_date($row_for_last_visited['visited_date']); // date or day of last visited member
    $time_last_visited = date('H:i:s', strtotime($row_for_last_visited['visited_time']));  // time of last visited member
    $membership_status_last_visited = membership_status($row_for_last_visited['membership_to']);
    $days_left = calculate_days_left($row_for_last_visited['membership_to']);
    $membership_days_left_visited = (strlen($days_left) == 57 ? '(expired)' : $days_left); // '(Membership expired)' string length is 2057, change to '(expired)'
    
    $path_photo_for_last_visited; // assign path for member photo
    if(file_exists('images/' . $id_for_last_visited . '.jpg')){
        $path_photo_for_last_visited = 'images/' . $id_for_last_visited . '.jpg';
    }else{
        $path_photo_for_last_visited = 'images/no_photo.png';
    }
/*-------------------- get number of today visits ------------------------------------------------------------------- */
    $result_for_today_visits -> data_seek(0);
    $row_for_today_visits = $result_for_today_visits -> fetch_array(MYSQLI_ASSOC);
    
    $num_visits = $row_for_today_visits['count'];
    
    echo <<< _END
    <member_data>
        <id>$last_visited_id</id>
        <first_name>$last_visited_first_name</first_name>
        <last_name>$last_visited_last_name</last_name>
        <date>$date_last_visited</date>
        <time>$time_last_visited</time>
        <membership>$membership_status_last_visited</membership>
        <membership_days>$membership_days_left_visited</membership_days>
        <photo_path>$path_photo_for_last_visited</photo_path>
        <num_visits>$num_visits</num_visits>
    </member_data>
_END;
    
}
/*-------------------------- SETS UP THE VALUES ON STARTUP PROGRAM ---------------------------------------------------------------------- */
else{
    $today_date = date('Y-m-d');  // get today's date for counting today visits
    
    $query_for_last_visited = "select m.id, m.first_name, m.last_name, m.membership_to, l.visited_date, l.visited_time from members m "
            . "join last_visited l on m.id = l.id where l.timestamp_date = "
            . "(select max(timestamp_date) from last_visited)";
    $query_for_last_updated = "select m.id, m.first_name, m.last_name, m.membership_to, m.paid, u.updated_date from members m "
            . "join last_updated u on m.id = u.id where u.timestamp_date = "
            . "(select max(timestamp_date) from last_updated)";
    $query_for_today_visits = "select count(*)as count from last_visited where visited_date = '$today_date'";
    
    // execute queries
    $result_for_last_visited = $connection -> query($query_for_last_visited);
    $result_for_last_updated = $connection -> query($query_for_last_updated);
    $result_for_today_visits = $connection -> query($query_for_today_visits);
    
    if(!$result_for_last_visited){
        die("Error: Could not retrieve last visited member");
    }
    if(!$result_for_last_updated){
        die("Error: Could not retrieve last updated member");
    }
    if(!$result_for_today_visits){
        die("Error: Could not retrieve the number of today visists");
    }
    
/*-------------------- GET TOTAL NUMBER OF MEMBERS ------------------------------------------------------------------------------------------------------*/
    $total_members = calculate_total_members();
    
/*------------------- GET TOTAL NUMBER OF MEMBERS WITH 'no membership' STATUS --------------------------------------------------------------------------- */
    $total_no_membership_members = calculate_no_membership_members();
    
/*------------------- CALCULATE PERCENTAGE OF 'no membership' MEMBERS ----------------------------------------------------------------------------------- */
    $percentage_no_membership_members = round($total_no_membership_members / $total_members * 100);
    
/* ------------------- assign retrieved last visited member values to variables ------------------------------------------------------------------------- */   
    $result_for_last_visited -> data_seek(0);
    $row_for_last_visited = $result_for_last_visited -> fetch_array(MYSQLI_ASSOC);
    
    $id_for_last_visited = $row_for_last_visited['id'];
    $last_visited_first_name = $row_for_last_visited['first_name'];
    $last_visited_last_name = $row_for_last_visited['last_name'];
                        // call function 'show_last_visited_date()' in 'calculate_days.php'. 
    $date_last_visited = show_last_visited_date($row_for_last_visited['visited_date']); // date or day of last visited member
    $time_last_visited = date('H:i:s', strtotime($row_for_last_visited['visited_time']));  // time of last visited member
    $membership_status_last_visited = membership_status($row_for_last_visited['membership_to']); // call function in 'calculate_days.php'
    $days_left_for_visited = calculate_days_left($row_for_last_visited['membership_to']);
    $membership_days_left_visited = (strlen($days_left_for_visited) == 57 ? '<strong style="color: red; font-weight: normal;";>(expired)</strong>' : $days_left_for_visited); // '(Membership expired)' string length is 57, change to '(expired)'
    $show_all_visits_name = "Show all " . $last_visited_first_name . "'s visits";
    
    $path_photo_for_last_visited; // assign path for member photo
    if(file_exists('images/' . $id_for_last_visited . '.jpg')){
        $path_photo_for_last_visited = 'images/' . $id_for_last_visited . '.jpg';
    }else{
        $path_photo_for_last_visited = 'images/no_photo.png';
    }
    
/* ------------------- assign retrieved last updated member values to variables -------------------------------------------------------------------- */
    
    $result_for_last_updated -> data_seek(0);
    $row_for_last_updated = $result_for_last_updated -> fetch_array(MYSQLI_ASSOC);
    
    $id_for_last_updated = $row_for_last_updated['id'];
    $last_updated_first_name = $row_for_last_updated['first_name'];
    $last_updated_last_name = $row_for_last_updated['last_name'];
                        // call function 'show_last_visited_date()' in 'calculate_days.php'. 
    $date_last_updated = show_last_visited_date($row_for_last_updated['updated_date']); // date or day of last updated member
    $paid_membership = $row_for_last_updated['paid'];
    $membership_status_last_updated = membership_status($row_for_last_updated['membership_to']);
    $days_left_for_updated = calculate_days_left($row_for_last_updated['membership_to']);
    $membership_days_left_updated = (strlen($days_left_for_updated) == 57 ? '<strong style="color: red; font-weight: normal;">(expired)</strong>' : $days_left_for_updated); // '(Membership expired)' string length is 57, change to '(expired)'
    //$show_all_visits_name = "Show all " . $last_visited_first_name . "'s visits";
    
    $path_photo_for_last_updated; // assign path for member photo
    if(file_exists('images/' . $id_for_last_updated . '.jpg')){
        $path_photo_for_last_updated = 'images/' . $id_for_last_updated . '.jpg';
    }else{
        $path_photo_for_last_updated = 'images/no_photo.png';
    }
/*-------------------- get number of today visits ------------------------------------------------------------------- */
    $result_for_today_visits -> data_seek(0);
    $row_for_today_visits = $result_for_today_visits -> fetch_array(MYSQLI_ASSOC);
    
    $num_visits = $row_for_today_visits['count'];
    
    //$result_for_last_visited -> close();
    //$connection -> close();
}
