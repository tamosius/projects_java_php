
$(document).ready(function(){
    
    var page_number = 0;
    
    $.get("submit_answer.php", "page_number=0", function(data){
        $(".timer").after(data);
    });
});


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


