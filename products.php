<?php 
logged_in();
template_header('products');
template_footer();
?>
	<div class="products">
	<h1>Products</h1>
    <div class="container">
    <?php 
        
    	$query_producttable = "SELECT name, description, price, inventory, idproduct, image FROM products";
    	$result_producttable = $conn->query($query_producttable);
    	
    	if ($result_producttable->num_rows > 0) {
    	    while($product = $result_producttable->fetch_assoc()) {
                ?>
                <a href="index.php?page=product&id=<?=$product['idproduct']?>">
    	        <form method="post">
    	        	<img src="<?php echo $product["image"]?>"><br>
    	        	<?php echo $product["name"]?><br>
    	        	<?php echo $product["price"]." :-"?><br>
    	        </form>
    	        </a>
  			<?php 
    	    }
    	    $conn->close();
    	}
        ?>
        </div> 
    </div> 
     