<!DOCTYPE html>
<?php 
  logged_in();
  template_header('checkout');
  template_footer();
  //Hämtar först ut användarens id
  $uname = $_SESSION['uname'];
  $query_getuid= "select iduser from users where username='".$uname."'";
  $result_uid = mysqli_query($conn, $query_getuid);
  $uid = mysqli_fetch_array($result_uid);
  //Här hämtar jag ut alla produkter som finns i kundkorgen. 
  //Priset borde hämtas från cartproductstabellen men den är 
  //null där så hämtar från products direkt för nu
  $query_cart = "SELECT  products.name, products.price, cartproducts.amount 
  FROM carttouser 
  INNER JOIN cartproducts 
    ON carttouser.idcart = cartproducts.idcart 
  INNER JOIN products 
    ON cartproducts.idproduct = products.idproduct
  WHERE carttouser.iduser = '".$uid['iduser']."'";
  $result_cart = $conn->query($query_cart);
  if(isset($_POST['pay_button'])){  
 /*
  $query_create_order = "INSERT INTO orders(iduser)
  VALUES('".$uid['iduser']."')";
  mysqli_query($conn, $query_create_order);
  $order_id = $conn->insert_id;
  //Lägg in orderid och resten av informationen
  $query_orderdetails = "INSERT INTO orderproducts(idorder, idproduct, price, amount)
  SELECT ?, products.idproduct, products.price, cartproducts.amount 
  FROM carttouser 
  INNER JOIN cartproducts 
    ON carttouser.idcart = cartproducts.idcart 
  INNER JOIN products 
    ON cartproducts.idproduct = products.idproduct
  WHERE carttouser.iduser = ?";
  $stmt = $conn->prepare($query_orderdetails);
  $stmt -> bind_param('is',$order_id, $uid['iduser']);
  $stmt -> execute();
  //mysqli_query($conn, $query_orderdetails);
  */
}
?>
<!--<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
-->

<body>
  <div class="container">
    <table>
      <th>
     <tr>
       <td><h4><i class="fa fa-shopping-cart"></i> Products: </h4></span></td>
        <td><h4>Price: </h4></td>
        <td><h4>Quantity: </h4></td>
        <td><h4>Total: </h4></td>
     </tr>
   </th>
    <tbody>
      <?php
        //Everything from the cart is displayed in different cells a new row for every product.
      if($result_cart->num_rows > 0){
        $total_price = 0;
        while($cart = $result_cart->fetch_assoc()){
      ?>
      <tr>
        <td><?php echo $cart["name"]?></td>
        <td><?php echo $cart["price"];?></td>
        <!--
          Ändra button så att det är input och kan ändra kvantitet i sin kundkorg
        -->
        <td><?php echo $cart["amount"]?><button type="button">Change</button></td>
        <td class="total"><?php $total_product = $cart["price"] * $cart["amount"]; 
              $total_price = $total_price + $total_product;
              echo $total_product ?></td>
        <?php } ?>
        <!-----While-loop Ends------>
         </tr>
         <tr>
          <td class="total"><h4>Total Price:</h4> </td>
          <td class = "total"><h4> <?php  echo $total_price ?></h4></td>
         </tr>
         <tr><td>
          <form method="post"><input type="submit" name="pay_button" value="Pay"></button></form></td>
         </tr>
    </tbody>
         <?php } $conn->close(); ?> 
  </table>
  </div>
  </div>


</body>
</html>