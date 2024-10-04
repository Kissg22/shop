<?php
require_once('files/functions.php');

$email = trim($_POST['email']);
$password = trim($_POST['password']);

if (login_user($email, $password)) {
    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Sikeres belépés.'];
    header("Location: account-orders.php");
    exit();
} else {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Hibás felhasználónév vagy jelszó.'];
    header('Location: login.php');
    exit();
}



