<?php

template_header('home');
template_footer();

if(isset($_POST['submit'])){
    $_SESSION['loggedin'] = false;
    header('Location: index.php');
}
?>

<div id="background">
	<h1>The store</h1>
	<p style="margin-top: -120px; color: black">Under construction...</p>
	<form style="margin-top: -290px" method="post">
         <input type="submit" value="Log out" name="submit">
    </form>
</div>