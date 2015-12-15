$(document).ready(function(){
    if($('#authenticate_username_field').val().length === 0){
        $('#authenticate_username_field').focus();  /* focus on the username field when page is loaded */
    }else{
        $('#authenticate_password_field').focus();  
    }
    
    $('#authenticate_username_field, #authenticate_password_field').keydown(function(){
        $('#error_message h4').text('');
    });
    
    
    
    /*$('#username_password_block form').submitas(function(event){
        var user_details = $(this).serialize();
        
        $.post('authentication.php', user_details, function(data){
            var message = $(data).find('message').text();
            $('#error_message h4').text(message);
        });
        //event.preventDefault();
    });*/
    
});

