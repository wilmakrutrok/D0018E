<?php
admin_verify();
template_header('admin');
admin_menu();
?>
<div style="float: left">
<h3>Latest orders:</h3>
<table>
<thead>
<tr>
	<th>Order date:</th>
	<th>User:</th>
    <th>Total price:</th>
    <th>More information:</th>
</tr>
</thead>
<tbody>
<?php 
	//Print out latest orders
    	$query_orders = $conn -> prepare("SELECT orders.idorder, orders.iduser, orders.totalprice, orders.date, users.username FROM orders
                                        inner join users 
                                        on orders.iduser = users.iduser");
        $query_orders -> execute();
    	$result_orders = $query_orders -> get_result();
    	if ($result_orders->num_rows > 0) {
    	    while($order = $result_orders->fetch_assoc() ) {
    	  
                ?><tr>
                	<td><?php echo $order["date"]?></td>
                	<td><?php echo $order["iduser"].", ".$order['username']?></td>
    	        	<td><?php echo $order["totalprice"]." :-"?></td>
    	        	<td><table><tr>
                    	<td>Product:</td>
                        <td>Amount:</td>
                        <td>Price:</td>
                    </tr>
    	        	<?php 
    	        	$query_orderproducts = $conn -> prepare("SELECT products.name, orderproducts.amount, orderproducts.price 
                                        FROM orderproducts 
                                        INNER JOIN products
                                        ON orderproducts.idproduct = products.idproduct
                                        WHERE idorder = ?");
                    $query_orderproducts -> bind_param('i', $order['idorder']);
                    $query_orderproducts -> execute();
    	            $result_orderproducts = $query_orderproducts -> get_result();
    	            if ($result_orderproducts->num_rows > 0) {
    	                
    	                while($orderproduct = $result_orderproducts->fetch_assoc()) {
    	                    ?><tr>
    	                    <td><?php echo $orderproduct["name"]?></td>
    	                    <td><?php echo $orderproduct["amount"]?></td>
    	                    <td><?php echo $orderproduct["price"]." :-"?></td>
    	                    <?php 
    	                }}
    	            ?>
    	    	</tr>
    	    	</table>
    	    	</td>
  			<?php 
    	    }   
    	}
        ?>
        </tr>
</tbody>
</table>
</div>
<div style="float: left;">
<h3>Products out of stock:</h3>
<?php 	//Print out products that are sold out
	$query_empty = $conn -> prepare("SELECT name, price FROM products WHERE inventory = 0");
        $query_empty -> execute();
    	$result_empty = $query_empty -> get_result();
    	if ($result_empty->num_rows > 0) {
    	    while($empty = $result_empty->fetch_assoc()) {
    	        echo $empty["name"].", ".$empty["price"]." :-";
    	    }
    	}
?>
</div>
<?php template_footer();?>
