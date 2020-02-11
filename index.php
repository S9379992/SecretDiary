<?php

    session_start();

    $error = "";    

    if (array_key_exists("logout", $_GET)) {
        
        unset($_SESSION);
        setcookie("iduser", "", time() - 60*60);
        $_COOKIE["iduser"] = "";
        
    } else if ((array_key_exists("iduser", $_SESSION) AND $_SESSION['iduser']) OR (array_key_exists("iduser", $_COOKIE) AND $_COOKIE['iduser'])) {
        
        header("Location: loggedinpage.php");
        
    }

    if (array_key_exists("submit", $_POST)) {
        
        include("connection.php"); 
        
        
        
        if (!$_POST['email']) {
            
            $error .= "An email address is required<br>";
            
        } 
        
        if (!$_POST['password']) {
            
            $error .= "A password is required<br>";
            
        } 
        
        if ($error != "") {
            
            $error = "<p>There were error(s) in your form:</p>".$error;
            
        } else {
            
            if ($_POST['signUp'] == '1') {
            
                $query = "SELECT iduser FROM `user` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0) {

                    $error = "That email address is taken.";

                } else {

                    $query = "INSERT INTO `user` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";

                    if (!mysqli_query($link, $query)) {

                        $error = "<p>Could not sign you up - please try again later.</p>";

                    } else {

                        $query = "UPDATE `user` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE iduser = ".mysqli_insert_id($link)." LIMIT 1";

                        mysqli_query($link, $query);

                        $_SESSION['iduser'] = mysqli_insert_id($link);

                        if ($_POST['stayLoggedIn'] == '1') {

                            setcookie("iduser", mysqli_insert_id($link), time() + 60*60*24*365);

                        } 

                        header("Location:loggedpage.php");

                    }

                } 
                
            } else {
                    
                    $query = "SELECT * FROM `user` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
                    $result = mysqli_query($link, $query);
                
                    $row = mysqli_fetch_array($result);
                
                    if (isset($row)) {
                        
                        $hashedPassword = md5(md5($row['iduser']).$_POST['password']);
                        
                        if ($hashedPassword == $row['password']) {
                            
                            $_SESSION['iduser'] = $row['iduser'];
                            
                            if ($_POST['stayLoggedIn'] == '1') {

                                setcookie("iduser", $row['iduser'], time() + 60*60*24*365);

                            } 

                            header("Location:loggedpage.php");
                                
                        } else {
                            
                            $error = "That email/password combination could not be found.";
                            
                        }
                        
                    } else {
                        
                        $error = "That email/password combination could not be found.";
                        
                    }
                    
                }
            
        }
        
        
    }


?>


<?php include("header.php"); ?>
   <div class="container" id="homePageContainer">
   <!-- //<img src="background.jpg"> -->
      <h1> Secret diary </h1>
      <p><strong>Store your thoughts permanently and securely!</strong></p>
      <div id="error"><?php if($error!=""){
            echo '<div class="alert alert-danger" role="alert">'.$error.'</div>'; 
                             }?>
        </div>

                <form method="post" id="signUpForm">
                <p>Interested? Sign Up now!</p>
                        <div class="form-group">
                                <input class="form-control" type="email" name="email" placeholder="Your Email">
                        </div>
                        <div class="form-group">
                                <input class="form-control" type="password" name="password" placeholder="Password">
                        </div>
                        <div class="checkbox">   
                            <label>     
                                <input  type="checkbox" name="stayLoggedIn" value=1> Stay logged in
                            </label>  
                        </div>
                        <div class="form-group">       
                                <input class="form-control" type="hidden" name="signUp" value="1">
                        </div>
                        <div class="form-group">            
                                <input class="btn btn-success" type="submit" name="submit" value="Sign Up!">
                        </div>
                        <p><strong><a class="toggleForms"> Log In </a></strong></p>
                </form>

                <form method="post" id="logInForm">
                    <p>Log in using your username and password</p>
                    <div class="form-group">
                            <input  class="form-control" type="email" name="email" placeholder="Your Email">
                    </div>
                    <div class="form-group">
                            <input class="form-control" type="password" name="password" placeholder="Password">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in
                        </label>
                    </div>
                    <div class="form-group">
                            <input  class="form-control" type="hidden" name="signUp" value="0">
                    </div>
                    <div class="form-group">    
                            <input class="btn btn-success" type="submit" name="submit" value="Log In!">
                    </div>
                    <p><strong><a class="toggleForms"> Sign Up </a></strong></p>
                </form>

   </div> 
<?php include("footer.php");?>



