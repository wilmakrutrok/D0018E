<!DOCTYPE html>
<?php 
  logged_in();
  template_header('checkout');
  //Hämtar först ut användarens id
  $uname = $_SESSION['uname'];
  $query_getuid= "select iduser from users where username='".$uname."'";
  $result_uid = mysqli_query($conn, $query_getuid);
  $uid = mysqli_fetch_array($result_uid);
  //Här hämtar jag ut alla produkter som finns i kundkorgen. 
  //Priset borde hämtas från cartproductstabellen men den är 
  $query_cart = "SELECT  products.name, products.price, cartproducts.amount FROM carttouser 
  INNER JOIN cartproducts ON carttouser.idcart = cartproducts.idcart 
  INNER JOIN products ON cartproducts.idproduct = products.idproduct
  WHERE carttouser.iduser = '".$uid['iduser']."'";
  $result_cart = $conn->query($query_cart);
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
body {
  font-family: Arial;
  font-size: 17px;
  padding: 8px;
}
* {
  box-sizing: border-box;
}
.row {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  -ms-flex-wrap: wrap; /* IE10 */
  flex-wrap: wrap;
  margin: 0 -16px;
}
.col-25 {
  -ms-flex: 25%; /* IE10 */
  flex: 25%;
}
.col-50 {
  -ms-flex: 50%; /* IE10 */
  flex: 50%;
}
.col-75 {
  -ms-flex: 75%; /* IE10 */
  flex: 75%;
}
.col-25,
.col-50,
.col-75 {
  padding: 0 0px;
}
.container {
  background-color: #f2f2f2;
  padding: 5px 5px 5px 5px;
  border: 1px solid lightgrey;
  border-radius: 3px;
}
input[type=text] {
  width: 100%;
  margin-bottom: 20px;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 3px;
}

label {
  margin-bottom: 10px;
  display: block;
}
.icon-container {
  margin-bottom: 20px;
  padding: 7px 0;
  font-size: 24px;
}
.btn {
  background-color: #4CAF50;
  color: white;
  padding: 12px;
  margin: 10px 0;
  border: none;
  width: 100%;
  border-radius: 3px;
  cursor: pointer;
  font-size: 17px;
}
.btn {
  background-color: #45a049;
}
a {
  color: #2196F3;
}
hr {
  border: 1px solid lightgrey;
}

table{
  text-align: left;
}
th{
  text-align:left;
  float:left;
  padding:10px;
}
tbody{
  width: 95%;
}
span.price {
  float: right;
  color: grey;
}
span.cart {
  float: left;
  color: grey;
}
@media (max-width: 800px) {
  .row {
    flex-direction: column-reverse;
  }
  .col-25 {
    margin-bottom: 20px;
  }
}
.Productname{
  float:left;
  padding: 2% 2%;
}
form {
  float:right;
  border:0%;
}
</style>

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
        $total_price;
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
              echo $total_product ?></td>
        <?php } ?>
        <!-----While-loop Ends------>
         </tr>
         <tr>
          <td class="total"><h4>Total Price:</h4> </td>
          <td class = "total"><h4> <?php  echo $total_product ?></h4></td>
         </tr>
         <tr>
          <td><button type="button">Pay</button></td>
         </tr>
    </tbody>
         <?php } $conn->close(); ?> 
  </table>
  </div>
  </div>


</body>
</html>