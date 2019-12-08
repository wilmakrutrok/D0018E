<?php
logged_in();
template_header('home');

if(isset($_POST['search'])){
    $search_value=$_POST["search"];
    $the_product = $conn->prepare("SELECT * from products where name = ?");
    $the_product->bind_param('s', $search_value);
    $the_product->execute();
    $result = $the_product->get_result();
    if ($result->num_rows > 0) {
        while($product = $result->fetch_assoc()){
            header('Location:index.php?page=product&id='.$product["idproduct"]);
        }
    }
    else{
        //die ('Product does not exist');
        header('Location:index.php?page=home');
    }
}
?>

<div id="background">
	<h1>The store</h1>
	<p style="margin-top: -120px; color: black"><form action="" method="post">
<input type="text" name="search">
<input type="submit" name="submit2" value="Search">
</form></p>
</div>
<?php template_footer(); ?>
