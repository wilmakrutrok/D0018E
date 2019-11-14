<?php 
    //session_start();
    $servername = "";
    $user = "";
    $password = "";
    
    $conn = new mysqli($servername, $user, $password);
        if($conn->connect_error){
            die("Connection failed: ". $conn->connect_error);
        }
    
    $db_name = "";
    $db = mysqli_select_db($conn, $db_name);     
?>
