<?php
logged_in();
template_header('checkout');

//Hämtar först ut användarens id
$uname = $_SESSION['uname'];
$iduser = $_SESSION['iduser'];
$idorder = $_GET['orderid']; 

if(isset($_POST['submit2'])){
   header('Location:index.php?page=home');
}

/*
$query_order = $conn->prepare("SELECT * FROM orders
  INNER JOIN orderproducts
  ON orders.idorder = orderproducts.idorder
  WHERE orders.idorder = ?");
$query_order -> bind_param('i', $idorder);
$query_order -> execute();
$result = $query_order -> get_result();
*/
/*
$query_order = $conn->prepare("SELECT * FROM orders WHERE idorder = ?");
$query_order->bind_param('i', $idorder);
$query_order->execute();
$query_order->get_result();

$query_orderproducts = $conn->prepare("SELECT * FROM orderproducts WHERE idorder = ?");
$query_orderproducts->bind_param('i', $idorder);
$query_orderproducts->execute();
$query_orderproducts->get_result();
?>
*/
$query_orders = $conn -> prepare("SELECT * FROM orders WHERE idorder = ?");
$query_orders -> bind_param('i', $idorder);
$query_orders -> execute();
$result_orders = $query_orders -> get_result();

if ($result_orders->num_rows > 0) {
  while($order = $result_orders->fetch_assoc()) { ?>
<div class="container_cart" style="margin-bottom:200px">
<div class="div_cart_content">
<div class="checkout_page">
<div class="checkout_page_center">
      <h4>CHECKOUT CONFIRMATION</h4>
    </div>
    </div>
<table class="checkout_table">
  <thead>
  <tr>
    <td>Order ID</td>
    <td>Date</td>
    <td>Total Price</td>
  </tr>
    <tr>
    <td><?php echo $order['idorder'] ?></td>
    <td><?php echo $order['date'] ?></td>
    <td><?php echo $order['totalprice'] ?></td>
  </tr>
</thead>
  <tr>
    <td>Product:</td>
    <td>Amount:</td>
    <td>Price:</td>
  </tr>
      <?php 
      $query_orderproducts = $conn -> prepare("
                              SELECT name, amount, price 
                              FROM orderproducts 
                              WHERE idorder = ?");
          $query_orderproducts -> bind_param('i', $order['idorder']);
          $query_orderproducts -> execute();
        $result_orderproducts = $query_orderproducts -> get_result();
        if ($result_orderproducts->num_rows > 0) {
            
            while($orderproduct = $result_orderproducts->fetch_assoc()) {
                ?>
                <tr>
                  <td><?php echo $orderproduct["name"]?></td>
                  <td><?php echo $orderproduct["amount"]?></td>
                  <td><?php echo $orderproduct["price"]." :-"?></td>
                </tr>
                  <?php 
                  } } } } 
                  $query_orderproducts->close(); ?>
</tbody>
</table>
    <form action="" method="post">
        <input type="submit" name="submit2" value="Home">
    </form>
</div>
</div>
</div>
<?php template_footer();?>
