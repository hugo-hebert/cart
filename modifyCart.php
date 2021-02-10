<?php
include_once "ressources.php";
include_once "cart.php";

//Start the session (to keep track of the contents of the cart)
session_start();

//Check if the cart is already initialized
if(!isset($_SESSION['cart'])){
    //if the cart is not initialized, we have a problem, return to index
    header('Location: index.php');
}

$cart = unserialize($_SESSION['cart']);

//retrieve and Sanitize the inputs
$method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
$itemName = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$itemQt = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);

//extra Sanitation
if($itemQt <= 0){
    $itemQt = NULL;
}

//Makes sure all the necessary data is present
if(!empty($method) && !empty($itemName) && !empty($itemQt)){
    //check which operation we want to execute
    if($method === "add"){
        $cart->add($itemName, $itemQt);
    }elseif($method === "remove"){
        $cart->remove($itemName, $itemQt);
    }
}

//update the session
$_SESSION['cart'] = serialize($cart);

//redirect to index
header('Location: index.php');
?>