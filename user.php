<?php
  logged_in();
  template_header('user');
?>
<br>
<h1>Orders:</h1>
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
        $uname = $_SESSION['uname'];
        $iduser = $_SESSION['iduser'];
        
        //find user ID
        /*$query_getuid= "select iduser from users where username='".$uname."'";
        $result_uid = mysqli_query($conn, $query_getuid);
        $uid = mysqli_fetch_array($result_uid);*/

    	$query_orders = "SELECT idorder, totalprice, date FROM orders WHERE iduser = '".$iduser."'";
    	$result_orders = $conn->query($query_orders);
    	
    	if ($result_orders->num_rows > 0) {

    	    while($order = $result_orders->fetch_assoc()) {
    	        

    	        
                ?><tr>
                	<td><?php echo $order["date"]?></td>
    	        	<td><?php echo $order["totalprice"]?></td>
    	        	<td><table><tr>
                    	<td>Product:</td>
                        <td>Amount:</td>
                        <td>Price:</td>
                    </tr>
    	        	<?php 
    	        	$query_orderproducts = "SELECT products.name, orderproducts.amount, orderproducts.price 
                                        FROM orderproducts 
                                        INNER JOIN products
                                        ON orderproducts.idproduct = products.idproduct
                                        WHERE idorder = '".$order['idorder']."'";
    	            $result_orderproducts = $conn->query($query_orderproducts);
    	            if ($result_orderproducts->num_rows > 0) {
    	                
    	                while($orderproduct = $result_orderproducts->fetch_assoc()) {
    	                    ?><tr>
    	                    <td><?php echo $orderproduct["name"]?></td>
    	                    <td><?php echo $orderproduct["amount"]?></td>
    	                    <td><?php echo $orderproduct["price"]?></td>
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