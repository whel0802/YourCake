<?php
session_start();
require_once('config.php');

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = trim($_POST['password']);

if (empty($username) || empty($password)) {
    echo 'Please fill in both fields.';
    exit;
}

// check muna sa customer table
$sql_customer = "SELECT * FROM customertable WHERE Cust_username = ? AND password = ? LIMIT 1";
$stmt_cust = $db->prepare($sql_customer);
$result_cust = $stmt_cust->execute([$username, $password]);

if ($result_cust && $stmt_cust->rowCount() > 0) {
    $user = $stmt_cust->fetch(PDO::FETCH_ASSOC);
    $_SESSION['userlogin'] = $user;
    $_SESSION['role'] = 'customer';
    echo 'customer';
    exit;
}

// check sa seller table
$sql_seller = "SELECT * FROM sellertable WHERE Seller_Username = ? AND password = ? LIMIT 1";
$stmt_seller = $db->prepare($sql_seller);
$result_seller = $stmt_seller->execute([$username, $password]);

if ($result_seller && $stmt_seller->rowCount() > 0) {
    $user = $stmt_seller->fetch(PDO::FETCH_ASSOC);
    $_SESSION['userlogin'] = $user;
    $_SESSION['role'] = 'seller';
    echo 'seller';
    exit;
}

echo 'Incorrect username or password.';