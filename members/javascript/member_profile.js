
$(document).ready(function(){
/*--------------- show 'member_profile' on double click on member table row ----------------------------------------------------------------------------*/
    $('.content').delegate('.row_data, .last_attended_member, .last_updated_member', 'dblclick', function(){
        var id = $(this).find('#member_id').val();
        
        $('.member_profile_right_sidebar div').removeClass('member_data_update');
        $('.member_profile_right_sidebar div').addClass('member_data');
        
        $('.member_profile_left_sidebar #photo_button').css({'visibility' : 'hidden', 'display' : 'block'});
        $('.member_profile_left_sidebar #shoot_button').css('display', 'none');
        
        $('.add_member_left_sidebar #shoot_button, .member_profile_left_sidebar #shoot_button, .add_member_left_sidebar #photo_camera, .member_profile_left_sidebar #photo_camera').css('display', 'none');
        $('.add_member_left_sidebar #photo_button, .member_profile_left_sidebar #photo_button, .add_member_left_sidebar #image, .member_profile_left_sidebar #image').show();  /* show the same image if user does not update it */
        
        $('.member_profile_bottom_panel #delete_button').prop('disabled', false).css('opacity', 1); /* enable 'delete' button */
        
        $.post('member_profile2.php', {'member_profile_id' : id}, function(data){
            var member_profile_id = $(data).find('member_id').text();
            var member_profile_first_name = $(data).find('first_name').text();
            var member_profile_last_name = $(data).find('last_name').text();
            var member_profile_address = $(data).find('address').text();
            var member_profile_ph_number = $(data).find('ph_number').text();
            var member_profile_date_of_birth = $(data).find('date_of_birth').text();
            var member_profile_email = $(data).find('email').text();
            var member_profile_membership_from = $(data).find('membership_from').text();
            var member_profile_membership_to = $(data).find('membership_to').text();
            var member_profile_paid = $(data).find('paid').text();
            var member_profile_date_created = $(data).find('date_joined').text();
            var member_profile_photo_path = $(data).find('photo_path').text();
            
            $('.member_data #member_id').val(member_profile_id);
            $('.member_data label').text(member_profile_id);
            $('.member_data #first_name').val(member_profile_first_name).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '22px'});
            $('.member_data #last_name').val(member_profile_last_name).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '22px'});
            $('.member_data #address').val(member_profile_address).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_data #ph_number').val(member_profile_ph_number).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_data #date_of_birth').val(member_profile_date_of_birth).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_data #email').val(member_profile_email).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_data #from').val(member_profile_membership_from).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_data #to').val(member_profile_membership_to).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_data #paid').val(member_profile_paid).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_data #date_joined').val(member_profile_date_created).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_profile_left_sidebar img').attr('src', member_profile_photo_path).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            
            
            $('.bottom_update_panel').hide();
            $('.member_profile_right_sidebar').css({'border' : 'none', 'box-shadow' : 'none'});
            $('.member_data input').attr('disabled', 'disabled');
            /* set font colors for 'no membership' and 'N / A' */
            set_font_colors();
            
            
            /* now fadeIn 'member_profile' window */
            $('.background_overlay, .member_profile_content').fadeIn(200);
            
        });
    });
/*-------------------------- UPDATE MEMBER PROFILE ----------------------------------------------------------------------------------------- */    
    $('.member_profile_bottom_panel #update_button').click(function(){
        /* get date of existing membership_to */
        $('.member_data #membership_to_before_update').val($('.member_data #to').val());
        
        $('.bottom_update_panel').show(500);
        
        $('.member_profile_right_sidebar div').removeClass('member_data');
        $('.member_profile_right_sidebar div').addClass('member_data_update');
        $('.member_profile_right_sidebar').css({'border' : 'solid 1px #D3D3D3', 'box-shadow' : '4px 4px 4px black'});
        $('.member_data_update input').removeAttr('disabled');
        $('.member_profile_left_sidebar #photo_button').css('visibility', 'visible');
        
        change_font_colors();  /* change font color etc in update profile field */
        
        $('.member_profile_bottom_panel #delete_button').prop('disabled', true).css('opacity', 0.4); /* disable 'delete' button when user clicks on 'update' button */
        
    });
/*------------------------ CANCEL UPDATE MEMBER PROFILE ----------------------------------------------------------------------------------- */
    $('#cancel_update').click(function(){
        var id = $('.member_profile_right_sidebar label').text();
        
        
        $('.member_profile_right_sidebar div').removeClass('member_data_update');
        $('.member_profile_right_sidebar div').addClass('member_data');
        $('.member_profile_left_sidebar #photo_button').css({'visibility' : 'hidden', 'display' : 'block'});
        $('.member_profile_left_sidebar #shoot_button').css('display', 'none');
        
        $.post('member_profile2.php', {'member_profile_id' : id}, function(data){
            
            /* data from 'member_profile2.php' */
            $('.member_data #first_name').val($(data).find('first_name').text()).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '22px'});  
            $('.member_data #last_name').val($(data).find('last_name').text()).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '22px'});
            $('.member_data #address').val($(data).find('address').text()).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_data #ph_number').val($(data).find('ph_number').text()).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_data #date_of_birth').val($(data).find('date_of_birth').text()).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});  
            $('.member_data #email').val($(data).find('email').text()).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_data #from').val($(data).find('membership_from').text()).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_data #to').val($(data).find('membership_to').text()).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            $('.member_data #paid').val($(data).find('paid').text()).css('color', 'white');
            $('.member_data #date_joined').val($(data).find('date_joined').text()).css({'color' : 'white', 'letter-spacing' : '1px', 'font-size' : '17px'});
            
            //var photo_path = $(data).find('photo_path').text();
            //$('img').attr('src', photo_path);
            
            $('.member_profile_left_sidebar #photo_camera').hide();
            $('.member_profile_left_sidebar #image').show();    /* show previous image if user cancels update */
            
            set_font_colors();  /* set values to previous condition if cancels update profile */
        });
        $('.bottom_update_panel').hide(500);
        $('.member_profile_right_sidebar').css({'border' : 'none', 'box-shadow' : 'none'});
        $('.member_data input').attr('disabled', 'disabled');
        
        $('.member_profile_bottom_panel #delete_button').prop('disabled', false).css('opacity', 1); /* enable 'delete' button when user clicks on 'cancel' button */
        
    });

/* ------------------- TEXTBOXES IN MEMBER PROFILE WINDOW, CHANGE FONT SIZES --------------------------------------------------------------------------------------- */
    $('.member_data input').keyup(function(){
        if(!this.value){
            if(($(this).attr('id') === 'first_name') || ($(this).attr('id') === 'last_name')){
                $(this).css({'color' : '#525252', 'font-size' : '22px'});
            }else{
                $(this).css({'color' : '#525252', 'font-size' : '17px'});
            }
            
        }
    });
});

/* change font colors after user clicks 'cancel update' button */
function set_font_colors(){
    $('.member_data input').each(function(){
        if($(this).val() === 'no membership' || $(this).val() === 'N / A'){
            $(this).css({'font-size' : '12px', 'color' : '#FF6262'});
        }
    });
}
/* change font colors after user clicks 'update button' */
function change_font_colors(){
    $('.member_data_update input').each(function(){
        $(this).css('color', '#525252');
        
        if($(this).val() === 'no membership' || $(this).val() === 'N / A'){
            $(this).css({'font-size' : '12px', 'color' : '#FF6262'});
        }
    });
    
}


