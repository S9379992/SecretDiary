<?php
    session_start(); 
    if(array_key_exists("content",$_POST)){
        
        include("connection.php"); 
        //   echo $_POST['content']; 
        $query= "UPDATE `user` SET diary='".mysqli_real_escape_string($link,$_POST['content'])."' 
                               WHERE iduser=".mysqli_real_escape_string($link, $_SESSION['iduser'])." LIMIT 1"; 
       
      mysqli_query($link, $query); 
       

    }
?>


