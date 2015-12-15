<?php
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
        
        <link href="css/newBootstrap.css" rel="stylesheet" type="text/css" />
        <link href="css/member_profile.css" rel="stylesheet" type="text/css" />
        <script src="javascript/jquery-1.11.2.js"></script>
        
        <script src="javascript/add_update_validation.js"></script>
        
        <script src="javascript/bootstrap.min.js" ></script>
        <script src="javascript/bootbox.js"></script>
        <script src="javascript/member_profile.js"></script>
        <script src="javascript/webcam.js"></script>
        <title></title>
    </head>
    <body>
        <div class="member_profile_content">
            
            <div class="member_profile_top_panel">
                <h1>Member Profile</h1>
            </div>
            <hr>
            <div class="member_profile_left_sidebar">
                <div id="image"><img src="<?php echo $photo_path; ?>" alt="foto" /></div>
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
                    <label><?php echo $id; ?></label>
                    <input type="hidden" id="member_id" name="member_id" value="<?php echo $id; ?>" />
                </div>
                <div class="member_data">
                    <span>First Name:</span>
                    <input id="first_name" type="text" name="first_name" value="<?php echo $first_name; ?>" disabled="disabled" placeholder="enter First Name here.." />
                </div>
                <div class="member_data">
                    <span>Last Name:</span>
                    <input id="last_name" type="text" name="last_name" value="<?php echo $last_name; ?>" disabled="disabled" placeholder="enter Last Name here.."/>
                </div>
                <div class="member_data">
                    <span>Address:</span>
                    <input id="address" type="text" name="address" value="<?php echo $address; ?>" disabled="disabled" placeholder="enter Address here.."/>
                </div>
                <div class="member_data">
                    <span>Phone No.:</span>
                    <input id="ph_number" type="text" name="ph_number" value="<?php echo $ph_number; ?>" disabled="disabled" placeholder="enter Ph.No. here.." />
                </div>
                <div class="member_data">
                    <span>Date of Birth:</span>
                    <input id="date_of_birth" type="text" name="date_of_birth" value="<?php echo $date_of_birth; ?>" disabled="disabled" placeholder="e.g. format '01-06-2015'" />
                </div>
                <div class="member_data">
                    <span>Email:</span>
                    <input id="email" type="text" name="email" value="<?php echo $email; ?>" disabled="disabled" placeholder="e.g. format 'tomas@gmail.com'" />
                </div>
                <div class="member_data">
                    <span>From:</span>
                    <input id="from" type="text" name="from" value="<?php echo $membership_from; ?>" disabled="disabled" placeholder="e.g. format '01-06-2015'" />
                </div>
                <div class="member_data">
                    <span>To:</span>
                    <input id="to" type="text" name="to" value="<?php echo $membership_to; ?>" disabled="disabled" placeholder="e.g. format '01-06-2015'"/>
                </div>
                <div class="member_data">
                    <span>Paid €:</span>
                    <input id="paid" type="text" name="paid" value="<?php echo $paid; ?>" disabled="disabled" placeholder="Paid.."/>
                </div>
                <div class="member_data">
                    <span>Date Created:</span>
                    <input id="date_joined" type="text" name="date_joined" value="<?php echo $date_joined; ?>" disabled="disabled" placeholder="e.g. format '01-06-2015'"/>
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

        
              
    </body>
</html>


