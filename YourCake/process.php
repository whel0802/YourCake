<?php
require_once('config.php');

$firstname    = $_POST['firstname'];
$lastname     = $_POST['lastname'];
$email        = $_POST['email'];
$phonenumber  = $_POST['phonenumber'];
$password     = $_POST['password'];

// Check for existing email or phone
$checkPhone = $db->prepare("SELECT * FROM customertable WHERE PhoneNumber = ?");
$checkPhone->execute([$phonenumber]);
$checkEmail = $db->prepare("SELECT * FROM customertable WHERE Email = ?");
$checkEmail->execute([$email]);

if ($checkPhone->rowCount() > 0) {
    echo 'Phone number already exists.';
    exit();
} elseif ($checkEmail->rowCount() > 0) {
    echo 'Email already exists.';
    exit();
}

// Insert data
$sql = "INSERT INTO customertable (FirstName, LastName, Email, PhoneNumber, Password) VALUES (?, ?, ?, ?, ?)";
$stmt = $db->prepare($sql);
$result = $stmt->execute([$firstname, $lastname, $email, $phonenumber, $password]);

if ($result) {
    echo "Registration successful!";
} else {
    echo "Failed to register.";
}
?>