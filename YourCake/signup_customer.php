<?php
session_start();
if(!empty($_SESSION['name'])){
    header('location:Admin.php');
} else if(!empty($_SESSION['cusname'])){
    header('location:HomePage.php');
}

require_once('config.php');

$usernameErr = $phoneErr = $emailErr = $passwordErr = '';
$usernameVal = $firstname = $lastname = $middlename = $nameext = $phonenumber = $email = $street = $barangay = '';

if (isset($_POST['create'])) {
    $username     = $_POST['Username'];
    $firstname    = $_POST['fname'];
    $lastname     = $_POST['lname'];
    $middlename   = $_POST['Mname'];
    $nameext      = $_POST['Ename'];
    $phonenumber  = $_POST['pnumber'];
    $email        = $_POST['eadrress'];
    $street       = $_POST['street'];
    $barangay     = $_POST['barangay'];
    $password     = $_POST['password'];
    $cpassword    = $_POST['cpassword'];
    $hasError = false;

    $checkUser = $db->prepare("SELECT * FROM customertable WHERE Cust_Username = ?");
    $checkUser->execute([$username]);
    if ($checkUser->rowCount() > 0) {
        $usernameErr = "Username already exists.";
        $hasError = true;
    }

    $checkPhone = $db->prepare("SELECT * FROM customertable WHERE PhoneNumber = ?");
    $checkPhone->execute([$phonenumber]);
    if ($checkPhone->rowCount() > 0) {
        $phoneErr = "Phone number already registered.";
        $hasError = true;
    }

    $checkEmail = $db->prepare("SELECT * FROM customertable WHERE Email = ?");
    $checkEmail->execute([$email]);
    if ($checkEmail->rowCount() > 0) {
        $emailErr = "Email already registered.";
        $hasError = true;
    }

    if ($password !== $cpassword) {
        $passwordErr = "Passwords do not match.";
        $hasError = true;
    }

    if (!$hasError) {
        $sql = "INSERT INTO customertable (
            Cust_Username, FirstName, MiddleName, LastName, NameExt, PhoneNumber, Email, Street, Barangay, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$username, $firstname, $middlename, $lastname, $nameext,$phonenumber, $email, $street, $barangay,$password]);

        if ($result) {
            echo '<script>
                alert("Registration successful!");
                window.location.href = "login.php";
            </script>';
        } else {
            echo '<script>
                alert("Error occurred while saving data.");
            </script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signupstyle.css">
    <link rel="shortcut icon" type="image/x-icon" href="/itcc1023/midterms/img/logo.png" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Customer Sign Up</title>
</head>
<body>

    <div class="form custForm">
            <a href="login.php" class="back">Back to Log In Page</a>
        <form action="signup_customer.php" method="POST">  
            <div class="first">
                <h2>FULL NAME</h2>
                <input type="text" name="fname" id="idFirstName" class="data" placeholder="First Name" required value="<?= htmlspecialchars($firstname ?? '') ?>">
                <input type="text" name="lname" id="idLastName" class="data" placeholder="Last Name" required value="<?= htmlspecialchars($lastname ?? '') ?>">
                <input type="text" name="Mname" id="idMiddleName" class="data" placeholder="Middle Name" required value="<?= htmlspecialchars($middlename ?? '') ?>">
                <input type="text" name="Ename" id="idEName" class="data" placeholder="Name Ext." value="<?= htmlspecialchars($nameext ?? '') ?>">
                </div>
            <div class="first">
            <br>
                <h2>Username</h2>
                <input type="text" name="Username" id="idUsername" class="data" placeholder="Username"required value="<?= htmlspecialchars($username ?? '') ?>">
                <p class="error"><?= $usernameErr ?></p>
            </div>

            <div class="first">
                <br>
                <h2>CONTACT INFORMATION</h2>
                <input type="tel"name="pnumber" id="idPhoneNumber" class="data" placeholder="Phone Number" required value="<?= htmlspecialchars($phonenumber ?? '') ?>" pattern="\d{11}" maxlength="11"oninput="this.value = this.value.replace(/[^0-9]/g, '')"title="Phone number must be exactly 11 digits">
                    <p class="error"><?= $phoneErr ?></p>
                    <input type="email" name="eadrress" id="idEmailAddress" class="data" placeholder="Email Address"required value="<?= htmlspecialchars($email ?? '') ?>">
                    <p class="error"><?= $emailErr ?></p>
            </div>
           
            <div class="first">
                <br>
                <h2>COMPLETE ADDRESS</h2>
                <input type="text" name="street" id="idStreet" class="data" placeholder="Street" required value="<?= htmlspecialchars($street ?? '') ?>">
                <input type="text" name="housenumber" id="idHouseNumber" class="data" placeholder="House Number">
                <select name="barangay" id="idBarangay" class="data">
                    <option value="b1" <?= ($barangay ?? '') == 'b1' ? 'selected' : '' ?>>Barangay 1</option>
                    <option value="b2" <?= ($barangay ?? '') == 'b2' ? 'selected' : '' ?>>Barangay 2</option>
                    <option value="b3" <?= ($barangay ?? '') == 'b3' ? 'selected' : '' ?>>Barangay 3</option>
                    <option value="b4" <?= ($barangay ?? '') == 'b4' ? 'selected' : '' ?>>Barangay 4</option>
                </select>
        </div>
        <div class="first"> 
            <br>
            <h2>SECURITY</h2>
            <input type="password" name="password" id="idPassword" class="data" placeholder="Password" required>
            <input type="password" name="cpassword" id="idConfirmPassword" class="data" placeholder="Confirm Password" required>
            <p class="error"><?= $passwordErr ?></p>
        </div>
        <input type="submit" class="submit" name="create" value="Sign Up">
        </form>
    </div>

    <!--the logo-->
    <div class="logo">
        <img src="/itcc1023/midterms/img/logo.png" alt="">
        <h3>Customer</h3>
        <h2>Sign Up</h2>
    </div>

</body>
</html>
