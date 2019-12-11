<?php
logged_in();
template_header('checkout');

//Hämtar först ut användarens id
$uname = $_SESSION['uname'];
$iduser = $_SESSION['iduser'];
$idorder = $_GET['orderid']; 

/*
$query_order = $conn->prepare("SELECT * FROM orders
  INNER JOIN orderproducts
  ON orders.idorder = orderproducts.idorder
  WHERE orders.idorder = ?");
$query_order -> bind_param('i', $idorder);
$query_order -> execute();
$result = $query_order -> get_result();
*/
$query_order = $conn->prepare("SELECT * FROM orders WHERE idorder = ?");
$query_order -> bind_param('i', $idorder);
$query_order -> execute();

$queryorderproducts = $conn->prepare("SELECT * FROM ordersproducts WHERE idorder = ?");
$queryorderproducts -> bind_param('i', $idorder);
$queryorderproducts->execute();




$query_order->get_result();
$order = $query_order->fetch_assoc();
$query_orderproducts->get_result();

if ($orderproducts->num_rows > 0) { ?>
  <div class="container_cart" style="margin-bottom:200px">
    <div class="div_cart_content">
      <div class="checkout_page">
        <div class="checkout_page_center">
      <h4>Thank You For Your Order </h4>
    </div>
    </div>
    <div>
          <table>
            <thead>
                <tr>
                <td>Order ID</td>
                </tr>
                <tr>
                  <td><?php echo $order['idorder'] ?></td>
                </tr>
          </thead>
            <?php
          while($orderproducts = $query_orderproducts->fetch_assoc()) { ?>

                <td>
                  <tr>
                    <td>Product:</td>
                    <td>Amount:</td>
                    <td>Price:</td>
                  </tr>
                          <tr>
                          <td><?php echo $orderproducts["name"]?></td>
                          <td><?php echo $orderproducts["amount"]?></td>
                          <td><?php echo $orderproducts["price"]." :-"?></td>
                          <?php 
                      }}
                  ?>
  </div>
  </div>
</div> 
<?php template_footer();?>