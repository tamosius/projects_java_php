<?php


require_once "sanitize_string.php";
require_once "connect_database.php";

$error_message = '';
$username = '';

if(isset($_POST['authenticate_username_field'])){
    $username = sanitize_string($_POST['authenticate_username_field']);
    $password = sanitize_string($_POST['authenticate_password_field']);
    
    $query = "select validation from authentication where username = '$username' and password = '$password'";
    $result = $connection -> query($query);
    if(!$result){
        die("Error: Could not retrive user validation information from database.");
    }
    $result -> data_seek(0);
    $row = $result -> fetch_array(MYSQLI_ASSOC);
    
    $validation = $row['validation'];
    if($validation == 'VALID'){
        session_start();
        $_SESSION['valid'] = 'TRUE';
        header("Location: index.php");
        echo "<data><message>Erroras! Username and password does not match!</message></data>";
    }
    else{
        //echo "<data><message>Error! Username and password does not match!</message></data>";
        $error_message = "Error! Username and password does not match!";
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/authenticate_window.css" rel="stylesheet" type="text/css" />
        
        <script src="javascript/jquery-1.11.3.js"></script>
        <script src="javascript/authentication.js"></script>
    </head>
    <body>
        <div class="authenticate_window">
            
            <div class="content">
                <img id="logo" src="images/sparta.jpg" alt="logo" />
                
                <div class="authenticate_block">
                    <h3>Please enter your details to login:</h3>
                    
                    <div id="username_password_block">
                        <form action="authentication.php" method="post">
                        <div id="username_block">
                            <label id="authenticate_username_label">Username:</label>
                            <input type="text" name="authenticate_username_field" id="authenticate_username_field" value="<?php echo $username; ?>" placeholder="Please type in your username..." />
                        </div>
                        <div id="password_block">
                            <label id="authenticate_password_label">Password:</label>
                            <input type="password" name="authenticate_password_field" id="authenticate_password_field" placeholder="Please type in password..." />
                        </div>
                        <div id="button_block">
                            <button type="submit" id="submit_authentication">Login</button>
                        </div>
                        </form>
                    </div>
                    
                    <div id="error_message">
                        <h4><?php echo $error_message; ?></h4>
                    </div>
                </div>
                
            </div>
            
        </div>
    </body>
</html>






