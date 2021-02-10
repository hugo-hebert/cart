<?php

include_once 'cart.php';
include_once 'ressources.php';

//Start the session (to keep track of the contents of the cart)
session_start();

//Check if the cart is already initialized
if(!isset($_SESSION['cart'])){
    //if not, we initalize it
    $cart = new Cart();
    $_SESSION['cart'] = serialize($cart);
}else{
    //else we retrieve the saved cart
    $cart = unserialize($_SESSION['cart']);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="productList">
    <h3>Product List:</h3>
    <table>
        <tr>
            <th>Product name</th>
            <th>Price per unit</th>
            <th>Quantity</th>
            <th>Add to cart</th>
        </tr>
    <?php 
        //create the table rows for each products
        foreach($products as $product){
            ?>
                    <tr>
                        <form action="modifyCart.php" method="post">
                        <input type="hidden" name="name" value="<?php echo $product['name']; ?>" readonly>
                        <input type="hidden" name="method" value="add" readonly>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo displayPrice($product['price']); ?></td>
                        <td><input type="number" name="quantity" value="1" min="1"></td>
                        <td><input type="submit" value="Add to Cart"></td>
                    </tr>
                </form>
            <?php
        }
    ?>
    </table>
    </div>
    <?php
    //display the current cart
    echo $cart->display();
    ?>
</body>
</html>
