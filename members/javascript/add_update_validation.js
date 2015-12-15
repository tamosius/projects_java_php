
$(document).ready(function(){
    
    $('.member_data input').each(function(){
        if($(this).val() === 'no membership' || $(this).val() === 'N / A'){
            $(this).css({'font-size' : '12px', 'color' : '#FF6262'});
        }
    });
    
/*------------- validate fields in create or update forms -------------------------------------------------------------------------------------- */
    $('form').submit(function(event){
        var error_message = '';
        $('.error_window').empty();
        
        if($(this).find('#first_name').val() === ''){
            //$(this).find('#first_name').css({'border' : '1px solid red', 'border-radius' : '2px', 'background' : '#FFE2E2'});
            $(this).find('#first_name').addClass('error');
            $(':focus').blur();   /* unfocus this field */
            error_message += '<p >- The First Name field cannot be empty! Please enter first name.</p>';
            event.preventDefault();
        }
        if($(this).find('#last_name').val() === ''){
            //$(this).find('#last_name').css({'border' : '1px solid red', 'border-radius' : '2px', 'background' : '#FFE2E2'});
            $(this).find('#last_name').addClass('error');
            $(':focus').blur();  /* unfocus this field */
            error_message += '<p>- The Last Name field cannot be empty! Please enter last name.</p>';
            event.preventDefault();
        }
        
        if(($(this).find('#date_of_birth').val() !== '') && ($(this).find('#date_of_birth').val() !== 'N / A')){
            var date_field = $(this).find('#date_of_birth');
            var date = date_field.val();
            
            if(!isDate(date)){
                date_field.val('');
                date_field.addClass('error');
                $(':focus').blur(); /* unfocus this field */
                error_message += "<p>- Invalid date format (Date of Birth): <strong style=\'color: white; font-size: 24px;\'>'" + date + "'</strong>. Please re-enter.</p>";
                event.preventDefault();
            }
        }
        if(($(this).find('#email').val() !== '') && ($(this).find('#email').val() !== 'N / A')){
            var email_field = $(this).find('#email');
            var email = email_field.val();
            
            if(!validateEmail(email)){
                email_field.val('');
                email_field.addClass('error');
                $(':focus').blur(); /* unfocus this field */
                error_message += "<p>- Invalid email format: <strong style=\'color: white; font-size: 24px;\'>'" + email + "'</strong>. Please re-enter.</p>";
                event.preventDefault();
            }
        }
        if(($(this).find('#from').val() !== '') && ($(this).find('#from').val() !== 'no membership')){
            var date_field = $(this).find('#from');
            var date = date_field.val();
            
            if(!isDate(date)){
                date_field.val('');
                date_field.addClass('error');
                $(':focus').blur(); /* unfocus this field */
                error_message += "<p>- Invalid date format (From): <strong style=\'color: white; font-size: 24px;\'>'" + date + "'</strong>. Please re-enter.</p>";
                event.preventDefault();
            }
        }
        if(($(this).find('#to').val() !== '') && ($(this).find('#to').val() !== 'no membership')){
            var date_field = $(this).find('#to');
            var date = date_field.val();
            
            if(!isDate(date)){
                date_field.val('');
                date_field.addClass('error');
                $(':focus').blur(); /* unfocus this field */
                error_message += "<p>- Invalid date format (To): <strong style=\'color: white; font-size: 24px;\'>'" + date + "'</strong>. Please re-enter.</p>";
                event.preventDefault();
            }
        }
        if(error_message !== ''){
            $('.error_window').append(error_message + '<hr><img src="images/error.jpg" alt="error" />');
            $('.error_window').fadeIn(200);
            
        }else{
            /* submit form, validated ok, and get id of the submited form */
            var form_data = $(this).serialize();
            
 /*----------- send data to the 'display_members.php' file and get back with the results -----------------------------------------------------------------*/
            $.post('display_members.php', form_data, function(data){
                var full_name = $(data).find('#full_name').val();
                var message = $(data).find('#message').val();
                var updated_membership = $(data).find('#updated_membership').val();
                var total_members = $(data).find('#total_members').val();
                
                var no_membership_members = $(data).find('#no_membership_members').val();
                var percentage_no_membership_members = $(data).find('#percentage_no_membership_members').val();
                
                if(updated_membership === 'true'){
                    var member_id = $(data).find('#member_id').val();
                    var paid = $(data).find('#paid_data').val();
                    var today_date = $(data).find('#today_date').val();
                    var days_left = $(data).find('#days_left').val();
                    var membership_to = $(data).find('#membership_to').val();
                    var photo_path = $(data).find('#photo_path').val();
                    
                    $('.last_updated_member #member_id').val(member_id);
                    $('.last_updated_member #full_name').text(full_name);
                    $('.last_updated_member #date_updated').text(today_date);
                    $('.last_updated_member #paid_membership').text(paid);
                    $('.last_updated_member #membership_status').text(membership_to);
                    $('.last_updated_member #membership_status_days_left').html(" " + days_left);
                    $('.last_updated_member img').attr('src', photo_path);
                }
                
                $('.no_membership_members #no_membership_members').text(no_membership_members);
                $('.no_membership_members #total').text(total_members);
                $('.no_membership_members #percentage_no_membership').text("(" + percentage_no_membership_members + " %)");
                
                $('.top_panel .total_members_count').text("Total Members: " + total_members);
                $('table.body_table').html(data);
                $('.popup_window').html("<p><strong>\'" + full_name + "\'</strong> " + message + 
                        "</p><hr><img id='successfull_add_update_image' src='images/green_accept.jpg' alt='photo' />");
            });
            $('.last_name').show();
            $('.membership').show();   /* show again all headers in main window */
            $('.from').show();
            $('.to').show();
            $('.paid').show();
            $('.first_name').html('First Name');
            $('.last_name').html('Last Name');
            
            $('.popup_window').fadeIn().delay(3000).fadeOut(500);
            $('.background_overlay, .add_member_content, .member_profile_content').fadeOut(1000);
        }
        event.preventDefault();
    });
    
    /* click on 'add_member' or 'member_profile' window and 'error_window' will disappear */
    $('.add_member_content, .member_profile_content').click(function(){
        $('.error_window').fadeOut(200);
    });
    
    /* change placeholder colors in input fields back to normal when on focus */
    $('form #first_name, form #last_name, form #address, form #ph_number, form #date_of_birth, form #email, form #from, form #to, form #date_joined').focus(function(){
        $(this).removeClass('error');
        $('.show_error_message label').text('');
        $(this).select(function(){
            $(this).keydown(function(){
                $(this).css({'color' : '#525252', 'font-size' : '17px'});
            });
        });
        //$(this).css({'border' : '1px solid #555', 'border-radius' : '2px', 'background' : '#DFDFDF'});
    });
    
});

// function to validate date in the form
function isDate(date_string){
    var current_value = date_string;
    
    // declare regex
    var date_pattern = /^(\d{1,2})(\-|-)(\d{1,2})(\-|-)(\d{4})$/;
    var date_array = current_value.match(date_pattern); /* check if date format is ok */
    
    if(date_array === null){
        return false;
    }
    // checks for dd-mm-yyyy format
    day = date_array[1];
    month = date_array[3];
    year = date_array[5];
    
    if(day < 1 || day > 31){
        return false;
    }else if(month < 1 || month > 12){
        return false;
    }else if((month === 4 || month === 6 || month === 9 || month === 11) && day === 31){
        return false;
    }else if(month === 2){
        var is_leap_year = (year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0));
        if(day > 29 || (day === 29 && !is_leap_year)){
            return false;
        }
    }
    return true;
}
function validateEmail(email){
    var email_pattern = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    var validated = email.match(email_pattern);
    
    if(validated === null){
        return false;
    }
    return true;
}
