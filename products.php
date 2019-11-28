<?php 
logged_in();
template_header('products');
template_footer();
?>
	<div class="products">
	<h1>Products</h1>
	<ul>
    <?php 
        
    	$query_producttable = "SELECT name, description, price, inventory, idproduct, image FROM products";
    	$result_producttable = $conn->query($query_producttable);
    	
    	if ($result_producttable->num_rows > 0) {

    	    while($product = $result_producttable->fetch_assoc()) {
                ?>
                <li>
                <a href="index.php?page=product&id=<?=$product['idproduct']?>">
    	        	<img src="<?php echo $product["image"]?>"><br>
    	        	<?php echo $product["name"]?><br>
    	        	<?php echo $product["price"]." :-"?>
    	        </a>
    	        </li>
  			<?php 
    	    }
    	    $conn->close();
    	}
        ?>
        </ul>
    </div> 
     