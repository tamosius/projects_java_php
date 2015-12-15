<?php
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: authentication.php");
}
//require_once "authentication.php";
include_once "display_members.php";
include_once "last_visited_member.php";
require_once "member_profile2.php"
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link href="css/table.css" rel="stylesheet" type="text/css" />
        <link href="css/bottom_panel_blocks.css" rel="stylesheet" type="text/css" />
        <link href="css/add_member.css" rel="stylesheet" type="text/css" />
        <link href="css/member_profile.css" rel="stylesheet" type="text/css" />
        <link href="css/delete_member.css" rel="stylesheet" type="text/css" />
        <link href="css/welcome_window.css" rel="stylesheet" type="text/css" />
        
        <script src="javascript/jquery-1.11.3.js"></script>
        <script src="javascript/webcam.js"></script>
        <script src="javascript/table.js"></script>
        <script src="javascript/bottom_panel.js"></script>
        <script src="javascript/delete_member.js"></script>
        <script src="javascript/add_update_validation.js"></script>
        <script src="javascript/add_member.js"></script>
        <script src="javascript/member_profile.js"></script>
        <title></title>
    </head>
    <body>
<!------------------ WELCOME WINDOW --------------------------------------------------------------------------------------------------------------->
        <div class="welcome_window">
            <div class="welcome_block">
                <img id="logo_image" src="images/sparta.jpg" />
                <img id="loading_image" src="images/loading3.jpg" />
                <h3>Loading...</h3>
            </div>
        </div>
<!------------------ POP-UP WINDOW FOR SUCCESSFULLY ADDED/UPDATED/DELETED MEMBER PROFILE MESSAGE ------------------------------------------------------>
        <div class="popup_window"></div>
<!------------------ BACKGROUND OVERLAY, ON PRESS 'delete' BUTTON (confirm) ---------------------------------------------------------------------------->
        <div class="delete_member_background_overlay"></div>
<!------------------ DELETE MEMBER CONFIRM WINDOW ------------------------------------------------------------------------------------------------------>
        <div class="delete_member_confirm_window"><img src="images/error.jpg" alt="confirm_image" /><span id="confirm_message"></span>
            <hr>
            <div>
                <button type="button" id="confirm_delete_member">Yes</button>
                <button type="button" id="cancel_delete_member">No</button>
            </div>
        </div>
<!------------------ BACKGROUND OVERLAY, WHEN SHOWING 'add_member' or 'update_member' ------------------------------------------------------------------>
        <div class="background_overlay"></div>
<!------------------ ERROR WINDOW ---------------------------------------------------------------------------------------------------------------------->
        <div class="error_window"></div>
<!------------------ ADD MEMBER CONTENT ---------------------------------------------------------------------------------------------------------------->
        <div class="add_member_content">
            <form>
            <div class="add_member_top_panel">
                <h1>New Member</h1>
                <h3>Please fill out the form:</h3>
            </div>
            <hr>
            <div class="add_member_left_sidebar">
                <div id="image"><img src="images/no_photo.png" alt="foto" /></div>
                <div id="photo_camera"><script>
                    //$(document).ready(function(){
                        document.write( webcam.get_html(214, 240) );
                    //});
                        
                   
                    </script></div>
                <div id="take_photo"><button type="button" name="take_photo"  id="photo_button">New Photo</button>
                                     <button type="button" name="take_photo"  id="shoot_button">Take a Snapshot</button></div>
                <div id="generate_code"><button type="button" name="generate_code" id="generate_button">Generate Barcode</button></div>
            </div>
            <div class="add_member_right_sidebar">
                
                <input type="hidden" id="member_id" value="0" />
                <input type="hidden" name="add_member_submit" />
                <div class="add_member_data">
                    <span>First Name:<strong style="color: red;"> *</strong></span>
                    <input id="first_name" type="text" name="first_name" placeholder="enter First Name here.." value="" autofocus />
                </div>
                <div class="add_member_data">
                    <span>Last Name:<strong style="color: red;"> *</strong></span>
                    <input id="last_name" type="text" name="last_name" placeholder="enter Last Name here.." />
                </div>
                <div class="add_member_data">
                    <span>Address:</span>
                    <input id="address" type="text" name="address" placeholder="enter Address here.." />
                </div>
                <div class="add_member_data">
                    <span>Phone No.:</span>
                    <input id="ph_number" type="text" name="ph_number" placeholder="enter Ph.No. here.." />
                </div>
                <div class="add_member_data">
                    <span>Date of Birth:</span>
                    <input id="date_of_birth" type="text" name="date_of_birth" placeholder="e.g. format '01-06-2015'" />
                </div>
                <div class="add_member_data">
                    <span>Email:</span>
                    <input id="email" type="text" name="email" placeholder="e.g. format 'tomas@gmail.com'" />
                </div>
                <div class="add_member_data">
                    <span>From:</span>
                    <input id="from" type="text" name="from" placeholder="e.g. format '01-06-2015'"/>
                </div>
                <div class="add_member_data">
                    <span>To:</span>
                    <input id="to" type="text" name="to" placeholder="e.g. format '01-06-2015'" />
                </div>
                <div class="add_member_data">
                    <span>Paid €:</span>
                    <input id="paid" type="text" name="paid" placeholder="Paid.." />
                </div>
            </div>
            <!-- end right_sidebar -->
            <div class="show_error_message">
                <span style="font-size: 12px;"><strong style="color: red;font-size: 18px;">*</strong> - mandatory fields</span>
            </div>
            <div class="add_member_bottom_panel">
                <hr>
                <button type="submit" id="submit_add_member" name="submit_add_member">Submit</button>
                <button type="button" id="back_button" name="back_button"><<< Back</button>
            </div>
          </form> <!-- end form -->
        </div><!-- end content -->
<!----------------------------- END OF ADD MEMBER CONTENT --------------------------------------------------->
        <!----------------------------- MEMBER PROFILE CONTENT ------------------------------------------------------>
        <div class="member_profile_content">
            
            <div class="member_profile_top_panel">
                <h1>Member Profile</h1>
            </div>
            <hr>
            <div class="member_profile_left_sidebar">
                <div id="image"><img src="" alt="foto" /></div>
                <div id="photo_camera"><script>
                    
                        document.write( webcam.get_html(214, 240) );
                   
                    </script></div>
                <div id="take_photo"><button type="button" name="take_photo" id="photo_button">Update Photo</button>
                                     <button type="button" name="take_photo" id="shoot_button">Take a Snapshot</button></div>
                <div id="generate_code"><button type="button" name="generate_code" id="generate_button">Generate Barcode</button></div>
            </div>
            <div class="member_profile_right_sidebar">
                <form>
                <div class="member_data">
                    <span>Member ID:</span>
                    <label></label>
                    <input type="hidden" id="member_id" name="member_id" value="" />
                    <input type="hidden" name="update_member_submit" />
                    <input type="hidden" name="membership_to_before_update" id="membership_to_before_update" value="" />
                </div>
                <div class="member_data">
                    <span>First Name:</span>
                    <input id="first_name" type="text" name="first_name" value="" disabled="disabled" placeholder="enter First Name here.." />
                </div>
                <div class="member_data">
                    <span>Last Name:</span>
                    <input id="last_name" type="text" name="last_name" value="" disabled="disabled" placeholder="enter Last Name here.."/>
                </div>
                <div class="member_data">
                    <span>Address:</span>
                    <input id="address" type="text" name="address" value="" disabled="disabled" placeholder="enter Address here.."/>
                </div>
                <div class="member_data">
                    <span>Phone No.:</span>
                    <input id="ph_number" type="text" name="ph_number" value="" disabled="disabled" placeholder="enter Ph.No. here.." />
                </div>
                <div class="member_data">
                    <span>Date of Birth:</span>
                    <input id="date_of_birth" type="text" name="date_of_birth" value="" disabled="disabled" placeholder="e.g. format '01-06-2015'" />
                </div>
                <div class="member_data">
                    <span>Email:</span>
                    <input id="email" type="text" name="email" value="" disabled="disabled" placeholder="e.g. format 'tomas@gmail.com'" />
                </div>
                <div class="member_data">
                    <span>From:</span>
                    <input id="from" type="text" name="from" value="" disabled="disabled" placeholder="e.g. format '01-06-2015'" />
                </div>
                <div class="member_data">
                    <span>To:</span>
                    <input id="to" type="text" name="to" value="" disabled="disabled" placeholder="e.g. format '01-06-2015'"/>
                </div>
                <div class="member_data">
                    <span>Paid €:</span>
                    <input id="paid" type="text" name="paid" value="" disabled="disabled" placeholder="Paid.."/>
                </div>
                <div class="member_data">
                    <span>Date Created:</span>
                    <input id="date_joined" type="text" name="date_joined" value="" disabled="disabled" placeholder="e.g. format '01-06-2015'"/>
                </div>
                <div class="bottom_update_panel">
                    <button type="button" id="cancel_update" name="cancel_update">Cancel</button>
                    <button type="submit" id="submit_update" name="submit_update">Submit</button>
                </div>
                </form>
            </div>
            <div class="member_profile_bottom_panel">
                <hr>
                <button type="button" id="update_button" name="update_button">Update Profile</button>
                <button type="button" id="delete_button" name="delete_button">Delete Profile</button>
                <button type="button" id="back_button" name="back_button"><<< Back</button>
            </div>

        </div>
<!--------------------- END OF MEMBER PROFILE CONTENT ------------------------------------------------------->
 
<!--------------------- MAIN CONTENT ------------------------------------------------------------------------>
        <div class="content">
            <div class="top_panel"> 
                <span class="total_members_count"><?php echo "Total Members: " . $total_members; ?></span>
                <span class="search"><input type="text" name="search_text" id="search_text" placeholder="Search name..." />
                         <button type="button" id="add_member" name="add_member">Add New</button></span>
            </div>
            <table class="outer_table">
                <tr>
                    <td>
                        <table class="header_table" border="1">
                    <tr>
                        <th rowspan="2" class="first_name">First Name</th>
                        
                        <th rowspan="2" class="last_name">Last Name</th>
                        <th colspan="2" class="membership">Membership</th>
                        <th rowspan="2" class="paid">Paid €</th>
                        
                    </tr>
                    <tr id="from_to">
                        <th class="from">From</th>
                        <th class="to">To</th>
                    </tr>
                    
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="size"> <!-- set window size depending on monitor size -->
                            <table class="body_table" border="1">
                                <?php 
                                display();
                                ?>
                                
        
        
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            
            <div class="bottom_panel">
 <!------------------------ END OF MAIN CONTENT ------------------------------------------------------------------------------------------------------->
 <!------------------------ LAST ATTENDED MEMBER ------------------------------------------------------------------------------------------------------>   
                <div class="last_attended_member">
                <!----------- ALL THREE BLOCKS BELOW GETS VALUES FROM 'last_visited_member.php'ON STARTUP PROGRAM ---->   
                    <img src="<?php echo $path_photo_for_last_visited; ?>" alt="foto" />
                    <label id="last_visited_today">Last visited member:</label>
                    <div>
                        <input type="hidden" id="member_id" value="<?php echo $id_for_last_visited; ?>" />
                        <span>Name:</span>
                        <label id="full_name"><?php echo $last_visited_first_name . " " . $last_visited_last_name; ?></label>
                    </div>
                    <div>
                        <span>Time visited:</span>
                        <label id="date_visited"><?php echo $date_last_visited; ?></label> at <label id="time_visited"><?php echo $time_last_visited; ?></label>
                    </div>
                    <div>
                        <span>Membership status:</span>
                        <label id="membership_status"><?php echo $membership_status_last_visited; ?></label><label id="membership_status_days_left"> <?php echo $membership_days_left_visited; ?></label>
                    </div>
                    <label id="full_attendance"><?php echo $show_all_visits_name; ?></label>
                </div>
<!----------------------- LAST UPDATED MEMBER ----------------------------------------------------------------------------------------------------->         
                <div class="last_updated_member">
                    <img src="<?php echo $path_photo_for_last_updated; ?>" alt="foto" />
                    <label id="last_updated">Recently extended membership:</label>
                    <div>
                        <input type="hidden" id="member_id" value="<?php echo $id_for_last_updated; ?>" />
                        <span>Name:</span>
                        <label id="full_name"><?php echo $last_updated_first_name . " " . $last_updated_last_name; ?></label>
                    </div>
                    <div>
                        <span>Updated on:</span>
                        <label id="date_updated"><?php echo $date_last_updated; ?></label> paid €:<label id="paid_membership"><?php echo $paid_membership; ?></label>
                    </div>
                    <div>
                        <span>Membership until:</span>
                        <label id="membership_status"><?php echo $membership_status_last_updated; ?></label><label id="membership_status_days_left"><?php echo " " . $membership_days_left_updated; ?></label>
                    </div>
                    <label id="show_all_updated_memberships">Show all extended memberships</label>
                </div>
<!------------------------ GYM REPORTS ----------------------------------------------------------------------------------------------------------->
                <div class="reports">
                    <div class="last_visitors">
                        <ul>
                            <li>Full History<input type="hidden" value="" /></li>
                            <li>12 Weeks<input type="hidden" value="84" /></li>
                            <li>8 Weeks<input type="hidden" value="56" /></li>
                            <li>4 Weeks<input type="hidden" value="28" /></li>
                            <li>2 Weeks<input type="hidden" value="14" /></li>
                            <li>1 Week<input type="hidden" value="7" /></li>
                        </ul>
                        <label id="show_last_visitors">Show visitors for last:</label>
                    </div>
                    <div class="today_visits">
                        <label id="today_visits">Today's visits:</label>
                        <label id="num_visits"><?php echo $num_visits ?></label>
                    </div>
                    <div class="no_membership_members">
                        <label id=""><span style="color: #FF6262;">'no membership'</span> status:</label>
                        <label id="no_membership_members"><?php echo $total_no_membership_members; ?></label> /
                        <label id="total"><?php echo $total_members; ?></label>
                        <label id="percentage_no_membership"><?php echo "(" . $percentage_no_membership_members . " %)"; ?></label>
                    </div>
                </div>
<!-------------------------- SECOND BLOCK OF GYM REPORTS ---------------------------------------------------------------------------------------------->
                <div class="reports">
                    <div class="most_visited_days">
                        <ul>
                            <li>12 Weeks<input type="hidden" value="84" /></li>
                            <li>8 Weeks<input type="hidden" value="56" /></li>
                            <li>4 Weeks<input type="hidden" value="28" /></li>
                            <li>2 Weeks<input type="hidden" value="14" /></li>
                            <li>1 Week<input type="hidden" value="7" /></li>
                        </ul>
                        <label id="most_visited_days">Show attendance for last:</label>
                    </div>
    
                </div>
<!-------------------------- BOTTOM PANEL BUTTONS BLOCK ----------------------------------------------------------------------------------------------->
                <div class="buttons_block">
                        
                        <button type="button" id="back_to_main_window">Main</button>
                        
                </div>
            </div>

        </div>

    </body>
</html>
