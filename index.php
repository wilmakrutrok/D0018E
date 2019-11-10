<?php 
include "config.php";
?>


<!DOCTYPE html>
	<head>
		<title>The store</title>
		<link rel="stylesheet" href="style.css">
	</head>
    <body>
    	<header>
        	<ul>
        		<li><a href="index.php">Home</a></li>
        		<li><a href="products.php">Products</a></li>
        		<li style="margin-left: 26%">The store</li>
        		<li style="float:right"><a href="checkout.php">Checkout</a></li>
        	</ul>
        </header>
        <main>
        <div id="background">
            <h1>The store</h1>
        </div>
            <p>Under construction...</p>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Inventory</th>
                </tr>
                <?php
                    $sql = "SELECT name, description, price, inventory FROM Products";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["name"]. "</td><td>" . $row["description"] . "</td><td>"
                    . $row["price"]. "</td><td>" . $row["inventory"] .  "</td></tr>";
                    }
                    echo "</table>";
                    } else { echo "0 results"; }
                    $conn->close();
                ?>
            </table>
        </main>
        <footer>
    		<p>Contact information</p>
    	</footer>
    </body>
</html>
