<?php
require_once("files/functions.php");


$id = $_POST["id"];

$pro = get_product($id);
if ($pro == null) {
   die("Termék nem található");
}

$pro["quantity"] = ((int)($_POST["quantity"]));
$_SESSION["cart"][$id] = $pro;
alert("success", "Termék sikeresen hozzáadva a kosárhoz.");
header("Location: shop.php");
