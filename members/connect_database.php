<?php

$hostname = "localhost";
$database = "membership";
$username = "root";
$password = "";

$connection = new mysqli($hostname, $username, $password, $database);
if($connection -> connect_error){
    echo "Could not connect to database";
}

