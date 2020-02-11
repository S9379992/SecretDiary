<?php
        session_start(); 
        $diaryContent = ""; 
       
        if(array_key_exists('iduser', $_COOKIE)){
            $_SESSION['iduser'] = $_COOKIE['iduser']; 
        }

        if(array_key_exists('iduser', $_SESSION)){
          
            include("connection.php"); 
            $query = "SELECT diary from `user`  WHERE iduser=".mysqli_real_escape_string($link,$_SESSION['iduser'])." LIMIT 1"; 
            $row = mysqli_fetch_array(mysqli_query($link, $query)) ; 
            $diaryContent = $row['diary']; 
        }
        else{
            header("Location:index.php"); 
       }

       include("header.php"); 
?>


<nav class="navbar navbar-fixed-top navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Secret Diary</a>
     <div class="pull-xs-right">
        <a href = 'index.php?logout=1'> <button class="btn btn-outline-success " type="submit">Log out</button><a>
    </div>
    </nav>

       <div class="container-fluid" id="containerLoggedInPage">
            <textarea id="diary" class="form-control">
                   <?php echo $diaryContent;  ?>
            </textarea>
       </div>

<?php
       include("footer.php"); 
?>