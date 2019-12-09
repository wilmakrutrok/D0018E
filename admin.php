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
    	$query_orders = $conn -> prepare("SELECT idorder, iduser, totalprice, date FROM orders");
        $query_orders -> execute();
    	$result_orders = $query_orders -> get_result();
    	$orderNumber = 1;
    	if ($result_orders->num_rows > 0) {
    	    while(($order = $result_orders->fetch_assoc()) && ($orderNumber <= 3)) {
    	  
                ?><tr>
                	<td><?php echo $order["date"]?></td>
                	<td><?php echo $order["iduser"].", "."name here"?></td>
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
  			$orderNumber = $orderNumber + 1;
    	    }   
    	}
        ?>
        </tr>
</tbody>
</table>
</div>
<div style="float: left;">
<h3>Products out of stock:</h3>
<?php $query_empty = $conn -> prepare("SELECT name, price FROM products WHERE inventory = 0");
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