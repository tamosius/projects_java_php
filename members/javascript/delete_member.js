$(document).ready(function(){
/*--------------------- DELETE MEMBER PROFILE ------------------------------------------------------------------------------------------------ */    
    $('#delete_button').click(function(){
        
        $('.delete_member_confirm_window #confirm_message').empty();
        
        $('.delete_member_background_overlay, .delete_member_confirm_window').fadeIn(200);
        
        var delete_first_name = $('.member_data #first_name').val();
        var delete_last_name = $('.member_data #last_name').val();
        
        $('.delete_member_confirm_window #confirm_message').html("<div> - Are you sure want to delete <strong style=\'font-size: 22px;\'>'" + delete_first_name + " " + delete_last_name + "'</strong> profile?</div>");
        
    });


/*------------------- CONFIRM DELETE MEMBER PROFILE, 'Yes' BUTTON -------------------------------------------------------------------------------------- */
    $('.delete_member_confirm_window #confirm_delete_member').click(function(){
        
        var delete_member_id = $('.member_profile_right_sidebar #member_id').val();
        var delete_first_name = $('.member_data #first_name').val();
        var delete_last_name = $('.member_data #last_name').val();
        var message = "profile has been successfully deleted!";
        
        $.post('display_members.php', {'delete_member_id' : delete_member_id}, function(){
            
            $.post('display_members.php', {'action': ''}, function(data){
                var total_members = $(data).find("#total_members").val();
                
                $('.top_panel .total_members_count').text("Total Members: " + total_members);
                $('table.body_table').html(data);
            });
        });
        $('.background_overlay, .member_profile_content, .delete_member_background_overlay, .delete_member_confirm_window').fadeOut();
        $('.popup_window').html("<p><strong>\'" + delete_first_name + " " + delete_last_name + "\'</strong> " + message 
                + "</p><hr><img id='successfull_add_update_image' src='images/green_accept.jpg' alt='photo' />");
        $('.popup_window').delay(1000).fadeIn().delay(3000).fadeOut(500);
    });
    
/*------------------- CANCEL DELETE MEMBER PROFILE, 'No' BUTTON -------------------------------------------------------------------------------------- */
    $('.delete_member_confirm_window #cancel_delete_member').click(function(){
        $('.delete_member_background_overlay, .delete_member_confirm_window').fadeOut(500);
    });
});
