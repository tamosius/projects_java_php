<?php

function sanitize_string($string){
    //if(!preg_match("/^[a-zA-Z0-9]*$/", $string)){
        //return -1;
    //}
    $string = strip_tags($string);    // to strip HTML entirely from user input
    $string = htmlentities($string);  // remove any html from string, e.g. change '<b>hi</b>' into '&lt;b&gt;hi&lt;/b&gt;'
    $string = stripslashes($string);  // get rid of unwanted slashes
    
    return $string;
}

//$variable = $connection -> real_escape_string($variable); // to prevent escape characters from being injected into string that will be 
                                                          // presented to MySql
                                                          // remember that this function takes into account the current character set of MySql 
                                                          // connection, so it must be used with 'mysqli' connection object ($connection)

/*function sanitize_string($str){
    $string = stripslashes($str);
    $string = htmlentities($str);
    $string = strip_tags($str);
    
    return $string;
}
function sanitize_mysql($connection, $str){
    $string = $connection -> real_escape_string($str); // if mysqli procedural version, use '$string = mysqli_real_escape_string($connection, $str);'
    $string = sanitize_string($str);
    
    return $string;
}*/

