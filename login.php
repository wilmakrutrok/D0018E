<?php
template_header_login('login');
template_footer();
if(isset($_POST['submit'])){
    $uname = mysqli_real_escape_string($conn,$_POST['username']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    
    if ($uname != "" && $password != ""){
        
        $sql_query = "select count(*) as cntUser from users where username='".$uname."' and password='".$password."'";
        $result = mysqli_query($conn,$sql_query);
        $row = mysqli_fetch_array($result);
        $count = $row['cntUser'];
        
        if($count > 0){
            $_SESSION['uname'] = $uname;
            $_SESSION['loggedin'] = true;
            header('Location: index.php?page=home');
        }else{
            echo "Invalid username and password";
        }
    }
    else {
        echo "No username or password given";
    }
}
       
?>

    <div class="login">
    	<h1>Log in</h1>
    	<form action="" method="post">
            Username:<br>
            <input type="text" name="username" id="login_uname" placeholder="Enter Username"><br>
            Password:<br>
            <input type="text" name="password" id="login_pwd" placeholder="Enter Password"><br><br>
            <input type="submit" value="Login" name="submit">
        </form>
        <p>Don't have an account? <a href="index.php?page=signup" style="color:gray;">Sign up now.</a></p>
    </div>