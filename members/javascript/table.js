

$(document).ready(function(){
    
    $('.welcome_window').delay(4000).fadeOut(2000);
    
/*------------------- search text box for searching members by name 'search_members.php' file --------------------------------------------------------*/
    $('#search_text').keyup(function(){
        $value = $('#search_text').val();
        
        $('.last_name').show();
        $('.membership').show();   /* show again all headers in main window */
        $('.from').show();
        $('.to').show();
        $('.paid').show();
        $('.first_name').html('First Name');
        $('.last_name').html('Last Name');
        
        $.post("search_members.php", {"search_text" : $value}, function(data){
            if(data === ""){
                $('.row_data').empty();
                $('table.body_table').html('<h2 style="text-align:center">No results found for<br>"' + $value + '"</h2>').nextAll('.row_data').remove();
            }else if(data !== ""){
                $('.row_data').empty();
                $('table.body_table').html(data);
            }
        });
    });
});




