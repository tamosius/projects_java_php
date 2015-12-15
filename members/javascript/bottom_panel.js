
$(document).ready(function(){
    /* insert into 'last_visited' table last visited member id and date/time
     * and then show in the bottom panel div '.last_attended_member' block */
    $('.body_table').delegate('.row_data #check_in', 'click', function(){
        
        var last_visited_id = $(this).closest('.row_data').find('#member_id').val();
        
        $.get('last_visited_member.php', {'last_visited_id' : last_visited_id}, function(data){
            var id = $(data).find('id').text();
            var first_name = $(data).find('first_name').text();
            var last_name = $(data).find('last_name').text();
            var date = $(data).find('date').text();
            var time = $(data).find('time').text();
            var membership_status = $(data).find('membership').text();
            var membership_days_left = $(data).find('membership_days').text();
            var photo_path = $(data).find('photo_path').text();
            var num_visits = $(data).find('num_visits').text();
            
            var full_name = first_name + " " + last_name;
            var message = "just checked in, at time: ";
            
            $('.last_attended_member #member_id').val(id);
            $('.last_attended_member img').attr('src', photo_path);
            $('.last_attended_member #full_name').text(full_name);
            $('.last_attended_member #date_visited').text(date);
            $('.last_attended_member #time_visited').text(time);
            $('.last_attended_member #membership_status').html(membership_status === 'no membership' ? '<strong style="font-size:10px; color:#FF6262;">' + membership_status + '</strong>' : membership_status);
            $('.last_attended_member #membership_status_days_left').text(' ' + membership_days_left).css({'color' : membership_days_left.match(/\d+/) > 5 ? 'green' : 'red', 'font-weight' : 'normal'});
            $('.last_attended_member #full_attendance').text("Show all " + first_name + "'s visits");
            $('.reports #num_visits').text(num_visits);  /* show number of today visits in 'reports' block */
            
            $('.popup_window').html("<p><img id='check_in_image' src='images/" + id + ".jpg' alt='No Photo' /><strong>" + full_name + "</strong> "
                    + message + "<strong>" + time + "</strong></p>").fadeIn().delay(3000).fadeOut(500);
        });
    });
    
 /*------------- show full attendance of member currently displayed in 'last visited member' block----------------------------------------------------- */
    $('#full_attendance').click(function(){
        var id = $('.last_attended_member #member_id').val();
        
        $.post('search_members.php', {'last_visited_id' : id}, function(data){
            var name = $(data).find('#last_visited_member_name').val();
            var total_visits = $(data).find('#total_visits').val();
            $('.last_name').hide();
            $('.membership').hide();  /* hide all headers except 'first_name' */
            $('.from').hide();
            $('.to').hide();
            $('.paid').hide();
            $('.first_name').html("<p style='float: left; margin-left: 15px;'><span style='font-size: 26px; padding-right: 8px;'>" + name + "</span> has visited us on:</p><span style='float: right;margin: 25px 20px 0px 0px;'>Total visits: " + total_visits + "</span>");
            $('table.body_table').html(data);
        });
    });

/*----------------- SHOW ALL MEMBERS WHO UPDATED THEIR MEMBERSHIP STATUS ------------------------------------------------------------- */
    $('.last_updated_member #show_all_updated_memberships').click(function(){
        $.post('search_members.php', {'all_extended_memberships' : ''}, function(data){
            var number_of_members = $(data).find('#number_of_members').val();
            //var lowest_update_date = $(data).find('#lowest_update_date').val();
            
            $('.last_name').show();
            $('.membership').show();   /* show again all headers in main window */
            $('.from').show();
            $('.to').show();
            $('.paid').show();
            $('.first_name').html('Full Name');
            $('.last_name').html("Extended Membership on:<br>Showing total: " + number_of_members);
            $('table.body_table').html(data);
        });
    });
    
 /*------------------- SHOW TODAY VISITORS, NAMES. TIMES ----------------------------------------------------------------------------- */
    $('.reports .today_visits').click(function(){
        $.post('search_members.php', {'today_visits' : ''}, function(data){
            var date_day = $(data).find('#today_date_day').val();
            var number_of_visitors = $(data).find('#number_of_visitors').val();
            
            $('.last_name').hide();
            $('.membership').hide();  /* hide all headers except 'first_name' */
            $('.from').hide();
            $('.to').hide();
            $('.paid').hide();
            $('.first_name').html("<span style='float: left; margin-left: 15px;'>Today is <span style='font-size: 26px; padding-right: 8px;'>" + date_day + "</span>:</span><span style='float: right; padding: 7px 19px 0px 0px;'>Total visits today: " + number_of_visitors + "</span>");
            $('table.body_table').html(data);
        });
    });

/* ----------------- SHOW ALL MEMBERS WITH 'no membership' STATUS ---------------------------------------------------------------------------------- */
    $('.no_membership_members').click(function(){
        $.post('search_members.php', {'no_membership_status' : ''}, function(data){
            var total_members_no_membership = $(data).find('#number_of_members').val();
            var total_members = $(data).find('#total_members').val();
            
            $('.last_name').hide();
            $('.membership').hide();  /* hide all headers except 'first_name' */
            $('.from').hide();
            $('.to').hide();
            $('.paid').hide();
            $('table.body_table').html(data);
            $('.first_name').html("<span style='float: left; margin-left: 15px;'>Members who have 'no membership' status.</span>"
                    + "<span style='float: right; padding: 7px 19px 0px 0px;'>Total: " + total_members_no_membership 
                    + " out of " + total_members + "</span>");
        });
    });
    
/*------------------ CALCULATE ATTENDANCE BY DAYS (MOST VISITED DAYS) ------------------------------------------------------------------------------ */
    $('.most_visited_days li').click(function(){
        //var days = $(this).val(); /* get period of time (number of days) for which to calculate attendance of members */
        var days = $(":input", this).val();
        var weeks = parseInt(days / 7);
        $.post('search_members.php', {'most_visited_days' : days}, function(data){
            var date_from = $(data).find('#date_from').val(); /* show date from which the members attendance is counted */
            
            $('.last_name').hide();
            $('.membership').hide();  /* hide all headers except 'first_name' */
            $('.from').hide();
            $('.to').hide();
            $('.paid').hide();
            $('table.body_table').html(data);
            $('.first_name').html("<span style='float: left; margin-left: 15px;'>Members attendance for the last " + weeks + " " + (weeks === 1 ? 'week' : 'weeks') + " (Since '" + date_from + "'). Today's attendance is not included </span>");
            
        });
        $('.most_visited_days ul').fadeOut(300).stop();
    });
 
/* ----------------- SHOW LAST VISITORS FOR SELECTED NUMBER OF WEEKS OF FOR FULL HISTORY ------------------------------------------------------------- */
     $('.last_visitors li').click(function(){
        //var days = $(this).val(); /* get period of time (number of days) for which to calculate attendance of members */
        var days = $(":input", this).val();
        var weeks = parseInt(days / 7);
        $.post('search_members.php', {'last_visited_members' : days}, function(data){
            var total_visits = $(data).find('#number_of_visitors').val();
            var date_from = $(data).find('#date_from').val();  /* find the date from which the last visitors are displayed */
            
            $('.last_name').show();
            $('.membership').show();   /* show again all headers in main window */
            $('.from').show();
            $('.to').show();
            $('.paid').show();
            $('.first_name').html('Full Name');
            $('.last_name').html("Total visits: " + total_visits + "<br><span style='font-size: 12px;'>Showing for last " 
                    + weeks + " " + (weeks === 1 ? 'week' : 'weeks') + "<br>Since '" + date_from + "'</span>");
            $('table.body_table').html(data);
        });
        $('.most_visited_days ul').fadeOut(300).stop();
    });
/* ----------------- HOVER ON MEMBERS ATTENDANCE LINK (MOST VISITED DAYS) AND (SHOT LAST VISITORS) ----------------------------------------------------------------------------- */
    $('.most_visited_days, .last_visitors').hover(function(){
        $("ul", this).stop().fadeToggle(300);
    });
    

 /*----------------- RETURN TO MAIN TABLE ----------------------------------------------------------------------------------------------------------- */
    $('#back_to_main_window').click(function(){
        $.ajax({url: 'display_members.php',
            data: {action: 'display'},
            type: 'post',
            success: function(data){
                $('.last_name').show();
                $('.membership').show();   /* show again all headers in main window */
                $('.from').show();
                $('.to').show();
                $('.paid').show();
                $('.first_name').html('First Name');
                $('.last_name').html('Last Name');
                $('table.body_table').html(data);
            }
        });
    });
});


