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
        
        //find user ID
        /*$query_getuid= "select iduser from users where username='".$uname."'";
        $result_uid = mysqli_query($conn, $query_getuid);
        $uid = mysqli_fetch_array($result_uid);*/

    	$query_orders = $conn -> prepare("SELECT idorder, totalprice, date FROM orders WHERE iduser = ?");
        $query_orders -> bind_param('i', $iduser);
        $query_orders -> execute();
    	$result_orders = $query_orders -> get_result();
    	
    	if ($result_orders->num_rows > 0) {

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
<?php template_footer();?>