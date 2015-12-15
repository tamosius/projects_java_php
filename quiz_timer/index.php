<?php
include_once "authentication.php";
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="css/quiz_css.css" type="text/css" rel="stylesheet"/>
        <script src="javascript/jquery-1.11.2.js"></script>
        <script src="javascript/javascript.js"></script>
    </head>
    <body>
        
        <div class="main_container">
            <div id="timer"></div>
            
            <div class="content">
                
            </div>
            <div class="start_button_block">
                <button type="button" class="button" id="start_button" name="next_submit">Start Quiz</button>
            </div>
            <div class="container_footer"><button type="button" class="button" name="next_submit" id="right_button">Next >>></button></div>
            <div class="container_footer"><button type="button" class="button" name="back_submit" id="left_button"><<< Back</button></div>
            <div class="container_footer"><button type="button" class="button" name="finish_submit" id="finish_button">FINISH</button></div>
            <div class="container_footer"><span class='correct_message'>CORRECT!</span><span class="wrong_message">WRONG!</span>
            
        </div>
                
    </body>
</html>
