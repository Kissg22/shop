<?php
require_once('files/functions.php');

$email = trim($_POST['email']);
$password = trim($_POST['password']);
$password_1 = trim($_POST['password_1']);
$phone_number = trim($_POST['phone_number']);
$last_name = trim($_POST['last_name']);
$first_name = trim($_POST['first_name']);

if ($password != $password_1) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'A jelszó nem egyezik.'];
    header('Location: login.php');
    die();
}

$sql = "SELECT * FROM users WHERE email = '{$email}'";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Már létezik felhasználó azonos e-mail-címmel.'];
    header('Location: login.php');
    die();
}

$password_hashed = password_hash($password, PASSWORD_DEFAULT);
$created = time();

$sql = "INSERT INTO users (
        first_name,
        last_name,
        phone_number,
        password,
        email,
        user_type,
        created
) VALUES (
        '{$first_name}',
        '{$last_name}',
        '{$phone_number}',
        '{$password_hashed}',
        '{$email}',
        'customer',
        '{$created}'
)";

if ($conn->query($sql)) {

    login_user($email, $password);

    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Sikeres fiók létrehozás.'];
    header("Location: account-orders.php");
    die();
} else {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Sikertelen fiók létrehozás'];
    header('Location: login.php');
    die();
}
