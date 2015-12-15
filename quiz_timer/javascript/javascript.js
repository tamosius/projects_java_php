
$(document).ready(function(){
    var time; // time in minutes for a quiz
    
    $(document).on("keydown", disableF5); // disable F5 button
    window.onbeforeunload = function(){
        window.location.replace("http:/www.google.ie");
        return "DON'T EXIT BROWSER, PRESS 'STAY ON THE PAGE' ASAP!!";
    };
    
    $.get("quiz_pages.php", "page_number=0", function(data){
        $(".content").html(data);
        
        time = $("#time").val();
        $(".correct_message").hide();
        $(".wrong_message").hide();
        
        timeValue = time < 10 ? "0" + time + ":00" : time + ":00";
        $("#timer").text(timeValue);
        $("#start_button").show();
        $("#left_button").hide();
        $("#right_button").hide();
        $("#finish_button").hide();
        
        
    });
    
    $(".button").click(function(){
        var formData = $("#form").serialize();
        var buttonName = $(this).attr("name");
        var buttonId = $(this).attr("id");
        var queryString = formData + "&" + buttonName;
        
        $.get("quiz_pages.php", queryString, function(data){
            $(".content").html(data);
            displayPage(); // function to display page appropriatelly
        });
        
        if(buttonId === "start_button"){
            startTimer(time);
        }else if(buttonId === "finish_button"){
            stopTimer();
            time = "00:00";
            $("#timer").text(time).css("color", "#313131");
        }
        
        
        return false;
    });
}); // end ready
    var timer; // countdown timer 
    function displayPage(){
        var answer_option = $('#answer_option').val();
        var quiz_is_finished = $("#quiz_is_finished").val();
        var correct_answer = $("#correct_answer").val();
        var page_number = Number($("#page_number").val());
        var total_questions = Number($("#total_questions").val());

        $(".option :radio").each(function(){ // check if checkbox checked previously by user
            if($(this).val() === answer_option){
                $(this).attr("checked", "checked");
            };
        }); // end each
        
        $(".correct_message").hide();
        $(".wrong_message").hide();
        
        if(page_number === 1){
            $("#start_button").hide();
            $("#left_button").css("visibility", "hidden").show();
            $("#right_button").show();
            $("#finish_button").hide();
            $("#review_button").hide();
        }else if(page_number > 1 && page_number < total_questions){
            $("#start_button").hide();
            $("#left_button").css("visibility", "visible").show();
            $("#right_button").show();
            $("#finish_button").hide();
            $("#review_button").hide();
        }else if(page_number === total_questions){
            $("#start_button").hide();
            $("#left_button").show();
            $("#right_button").hide();
            $("#finish_button").show();
            $("#review_button").hide();
        }else if(page_number === 0){
            $("#start_button").attr("id", "review_button");
            $("#review_button").show();
            $("#review_button").text("Review Quiz");
            $("#left_button").hide();
            $("#right_button").hide();
            $("#finish_button").hide();
        }
        
        if(quiz_is_finished === "TRUE"){
        
            $(".option :radio").each(function(){
                $(this).attr("disabled", "disabled");
                if($(this).val() === correct_answer){
                    $(this).parent().css("background", "green");
                }
                if($(this).val() === answer_option && $(this).val() !== correct_answer){
                    $(this).parent().css("background", "red");
                }
            });
            if(answer_option === correct_answer){
                $(".correct_message").show();
                $(".wrong_message").hide();
            }else{
                $(".wrong_message").show();
                $(".correct_message").hide();
            }
        }
        if(page_number !== 0){
            $("body").css('background', 'url("images/images.jpg")');
        }else{
            $("body").css('background', 'url("images/images2.jpg")');
        }
        
        
    } // end function 'displayPage()'
    
    function startTimer(time){
            var duration = time * 60 - 1;
            var minutes, seconds;
            
            
            timer = setInterval(function(){
                minutes = parseInt(duration / 60);
                seconds = parseInt(duration % 60);
                
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                
                if(duration > 29){
                    $("#timer").text(minutes + ":" + seconds);
                }else{
                    $("#timer").text(minutes + ":" + seconds).css("color", "red");
                }
                
                if(duration === 0){
                    var formData = $("#form").serialize();
                    var buttonName = "finish_submit";
                    var queryString = formData + "&" + buttonName;
                    $.get("quiz_pages.php", queryString, function(data){
                        $(".content").html(data);
                        $("#start_button").attr("id", "review_button");
                        $("#review_button").show();
                        $("#review_button").text("Review Quiz");
                        $("#left_button").hide();
                        $("#right_button").hide();
                        $("#finish_button").hide();
                        $("body").css("background", "url('images/images2.jpg')");
                    });
                    stopTimer();
                    $("#timer").css("color", "#313131");
                }
                
                duration--;
                
            }, 1000);
    } // end function 'startTimer()'
    
    function stopTimer(){
        clearInterval(timer);
    }
        
    function disableF5(e){
        if(((e.which || e.keyCode) === 116) || ((e.keyCode === 82) && e.ctrlKey)){
            e.preventDefault();
        }
    }
    
    
    


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


