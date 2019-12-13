<?php
  logged_in();
  template_header('user');
  $uname = $_SESSION['uname'];
?>
<br>
<h1>Orders for <?php echo $uname?>:</h1>
<table>
<thead>
<tr>
	<th>Order date:</th>
    <th>Total price:</th>
    <th>More information:</th>
</tr>
</thead>
<tbody>
<?php 
        
        $iduser = $_SESSION['iduser'];
        
	//Get all orders for user
    	$query_orders = $conn -> prepare("SELECT idorder, totalprice, date FROM orders WHERE iduser = ?");
        $query_orders -> bind_param('i', $iduser);
        $query_orders -> execute();
    	$result_orders = $query_orders -> get_result();
    	
    	if ($result_orders->num_rows > 0) {
	    //Print orderinformation one order at the time
    	    while($order = $result_orders->fetch_assoc()) {
                ?><tr>	
                	<td><?php echo $order["date"]?></td>
    	        	<td><?php echo $order["totalprice"]." :-"?></td>
    	        	<td><table><tr>
                    	<td>Product:</td>
                        <td>Amount:</td>
                        <td>Price:</td>
                    </tr>
    	        	<?php 
			//Get specific order information for each order
    	        	$query_orderproducts = $conn -> prepare("SELECT name, amount, price 
                                        FROM orderproducts 
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
<?php template_footer();?>
