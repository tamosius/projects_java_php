<?php
require_once "quiz_pages.php"; // file with appropriate quiz pages

//$username = ;
$password = 'secret';

if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
    // looks for two particular values
    if($_SERVER['PHP_AUTH_USER'] != "" && $_SERVER['PHP_AUTH_PW'] == $password){
        // authentication ok, go to quiz
    }else{
        // pop-ups prompt for authentication
        header('WWW-Authenticate: Basic realm="Restricted Section"'); // if user clicks Cancel Button, the program proceeds to the following two lines,
        header('HTTP/1.0 401 Unauthorized');                          //  whick sends the following header and an error message
        die("<div align=\"center\"><h2 style='color: red;'>Please enter your username and password</h2>"
               . "<button type='button' onclick=\"location.href='authentication.php'\">Try Again</button></div>");
    }
    
}else{
    header('WWW-Authenticate: Basic realm="Restricted Section"');
    header('HTTP/1.0 401 Unauthorized');
    die("<div align=\"center\"><h2 style='color: red;'>Please enter your username and password</h2>"
            . "<button type='button' onclick=\"location.href='index.php'\">Try Again</button></div>");
}
?>

