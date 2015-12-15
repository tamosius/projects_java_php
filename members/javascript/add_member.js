$(document).ready(function(){
/* ---------- 'back' BUTTON RETURN TO MAIN TABLE FROM 'add_member' or 'member_profile' ------------------------------------------------------------------ */
    $('.add_member_bottom_panel #back_button, .member_profile_bottom_panel #back_button').click(function(){
        $('.background_overlay, .add_member_content, .member_profile_content').fadeOut(1000);
        $('.add_member_left_sidebar #shoot_button, .member_profile_left_sidebar #shoot_button, .add_member_left_sidebar #photo_camera, .member_profile_left_sidebar #photo_camera').css('display', 'none');
        $('.add_member_left_sidebar #photo_button, .member_profile_left_sidebar #photo_button, .add_member_left_sidebar #image, .member_profile_left_sidebar #image').show();  /* show the same image if user does not update it */
        
        /* if user clicks 'back' button instead 'cancel update' change class name in 'member_profile' */
        
        $('.member_profile_bottom_panel #delete_button').prop('disabled', false).css('opacity', 1); /* enable 'delete' button when user clicks on 'back' button */
        
        $.post('display_members.php', {'do_not_save_image' : ''}); /* delete image if created and not submited */
    });
    
/*---------- ADD NEW MEMBER TO THE DATABASE (button beside search textfield, top-right corner) ---------------------------------------------------------- */
    $('.top_panel #add_member').click(function(){
        $('.add_member_data input').each(function(){
            $(this).val(''); /* clear all text boxes */
            $(this).removeClass('error');  /* remove red font color in text boxes */
        });
        $('.background_overlay, .add_member_content').fadeIn(200);
        $('.add_member_right_sidebar #first_name').focus(); /* focus on 'first_name' textbox again */
    });
});

