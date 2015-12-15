

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        
        
        <link href="css/add_member.css" rel="stylesheet" type="text/css" />
        <script src="javascript/jquery-1.11.2.js"></script>
        
        <script src="javascript/add_update_validation.js"></script>
        
        
        <script src="javascript/member_profile.js"></script>
        <script src="javascript/webcam.js"></script>
        <title></title>
    </head>
    <body>
        <div class="add_member_content">
            <form action="index.php" method="post">
            <div class="add_member_top_panel">
                <h1>New Member</h1>
                <h3>Please fill out the form:</h3>
            </div>
            <hr>
            <div class="add_member_left_sidebar">
                <div id="image"><img src="images/no_photo.png" alt="foto" /></div>
                <div id="photo_camera"><script>
                    
                        document.write( webcam.get_html(214, 240) );
                   
                    </script></div>
                <div id="take_photo"><button type="button" name="take_photo"  id="photo_button">New Photo</button>
                                     <button type="button" name="take_photo"  id="shoot_button">Take a Snapshot</button></div>
                <div id="generate_code"><button type="button" name="generate_code" id="generate_button">Generate Barcode</button></div>
            </div>
            <div class="add_member_right_sidebar">
                
                <input type="hidden" id="member_id" value="0" />
                <div class="member_data">
                    <span>First Name:<strong style="color: red;"> *</strong></span>
                    <input id="first_name" type="text" name="first_name" placeholder="enter First Name here.." autofocus />
                </div>
                <div class="member_data">
                    <span>Last Name:<strong style="color: red;"> *</strong></span>
                    <input id="last_name" type="text" name="last_name" placeholder="enter Last Name here.." />
                </div>
                <div class="member_data">
                    <span>Address:</span>
                    <input id="address" type="text" name="address" placeholder="enter Address here.." />
                </div>
                <div class="member_data">
                    <span>Phone No.:</span>
                    <input id="ph_number" type="text" name="ph_number" placeholder="enter Ph.No. here.." />
                </div>
                <div class="member_data">
                    <span>Date of Birth:</span>
                    <input id="date_of_birth" type="text" name="date_of_birth" placeholder="e.g. format '01-06-2015'" />
                </div>
                <div class="member_data">
                    <span>Email:</span>
                    <input id="email" type="text" name="email" placeholder="e.g. format 'tomas@gmail.com'" />
                </div>
                <div class="member_data">
                    <span>From:</span>
                    <input id="from" type="text" name="from" placeholder="e.g. format '01-06-2015'"/>
                </div>
                <div class="member_data">
                    <span>To:</span>
                    <input id="to" type="text" name="to" placeholder="e.g. format '01-06-2015'" />
                </div>
                <div class="member_data">
                    <span>Paid €:</span>
                    <input id="paid" type="text" name="paid" placeholder="Paid.." />
                </div>
            </div>
            <!-- end right_sidebar -->
            <div class="show_error_message">
                <span style="font-size: 12px;"><strong style="color: red;font-size: 18px;">*</strong> - mandatory</span>
                <label></label>
            </div>
            <div class="add_member_bottom_panel">
                <hr>
                <button type="submit" id="submit_add_member" name="submit_add_member">Submit</button>
                <button type="button" id="back_button" name="back_button"><<< Back</button>
            </div>
          </form> <!-- end form -->
        </div><!-- end content -->

        
              
    </body>
</html>


