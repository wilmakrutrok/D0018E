<?php 
template_header_login('signup');

//Creating new user account if username don't exist in database and password match
if(isset($_POST['submit'])){
    $uname=$_POST['username'];
    $password=$_POST['password'];
    $password2=$_POST['password2'];
    if ($password != $password2){
        echo"Password does not match";
    }elseif ($uname != "" && $password != "") {
          $sql_query = $conn -> prepare("select count(*) as cntUser
          from users where username= ?");
          $sql_query -> bind_param('s', $uname);
          $sql_query -> execute();
          $result = $sql_query -> get_result();
          $row = mysqli_fetch_array($result);
          $count = $row['cntUser'];
          if($count > 0){
            echo'Username already exists.';
          }
          else{
            $hash=password_hash("$password", PASSWORD_DEFAULT);
            $sql_query2 = $conn -> prepare("INSERT INTO users (username, password)
                        VALUES (?, ?)");
            $sql_query2 -> bind_param('ss', $uname, $hash);
            $sql_query2 -> execute();
            //$newuser_add = mysqli_query($conn,$sql_query2);
            header('Location: index.php?page=home');
          }
    }else {
		echo "No username or password given ";
	}
}
?>

    <div class="login">
    	<h1>Sign up</h1>
    	<form action="" method="post">
            Username:<br>
            <input type="text" name="username" id="signup_uname" placeholder="Enter Username"><br>
            Password:<br>
            <input type="password" name="password" id="signup_pwd" placeholder="Enter Password"><br>
            Confirm Password:<br>
            <input type="password" name="password2" id="signup_pwd2" placeholder="Confirm Password"><br><br>
            <input type="submit" value="Sign up" name="submit">
        </form>
        <p>Already have an account? <a href="index.php?page=login" style="color:gray;">Login now.</a></p>
    </div>
<?php template_footer();?>       
