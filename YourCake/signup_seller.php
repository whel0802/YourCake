<?php
session_start();
if (!empty($_SESSION['name'])) {
    header('location:Admin.php');
    exit;
} else if (!empty($_SESSION['cusname'])) {
    header('location:HomePage.php');
    exit;
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
    $email        = $_POST['eadrress'];  // keep this as you said
    $street       = $_POST['street'];
    $barangay     = $_POST['barangay'];
    $password     = $_POST['password'];
    $cpassword    = $_POST['cpassword'];
    $housenumber  = $_POST['housenumber'] ?? '';
    $landmark     = $_POST['landmark'] ?? '';
    $shopname     = $_POST['shopname'];
    $shoptype     = $_POST['shoptype'];

    $hasError = false;

    // Duplicate checking
    $checkUser = $db->prepare("SELECT * FROM seller WHERE Seller_Username = ?");
    $checkUser->execute([$username]);
    if ($checkUser->rowCount() > 0) {
        $usernameErr = "Username already exists.";
        $hasError = true;
    }

    $checkPhone = $db->prepare("SELECT * FROM seller WHERE PhoneNumber = ?");
    $checkPhone->execute([$phonenumber]);
    if ($checkPhone->rowCount() > 0) {
        $phoneErr = "Phone number already registered.";
        $hasError = true;
    }

    $checkEmail = $db->prepare("SELECT * FROM seller WHERE Email = ?");
    $checkEmail->execute([$email]);
    if ($checkEmail->rowCount() > 0) {
        $emailErr = "Email already registered.";
        $hasError = true;
    }

    if ($password !== $cpassword) {
        $passwordErr = "Passwords do not match.";
        $hasError = true;
    }

    $idPath = $picPath = $logoPath = $certPath = '';
    $uploadDir = 'uploads/';

    if (!$hasError) {
        // Uploading Files
        if (!empty($_FILES['idUpload']['name'])) {
            $idPath = $uploadDir . basename($_FILES['idUpload']['name']);
            move_uploaded_file($_FILES['idUpload']['tmp_name'], $idPath);
        }

        if (!empty($_FILES['picUpload']['name'])) {
            $picPath = $uploadDir . basename($_FILES['picUpload']['name']);
            move_uploaded_file($_FILES['picUpload']['tmp_name'], $picPath);
        }

        if (!empty($_FILES['logoUpload']['name'])) {
            $logoPath = $uploadDir . basename($_FILES['logoUpload']['name']);
            move_uploaded_file($_FILES['logoUpload']['tmp_name'], $logoPath);
        }

        if (!empty($_FILES['certUpload']['name'])) {
            $certPath = $uploadDir . basename($_FILES['certUpload']['name']);
            move_uploaded_file($_FILES['certUpload']['tmp_name'], $certPath);
        }

        // Password hashing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO seller (
            Seller_Username, FirstName, MiddleName, LastName, NameExt, PhoneNumber, Email, Street, HouseNo, Barangay, Password, ShopName, ShopType, Landmark, SellerPicture, SellerLogo, Requirement, Status, Customization
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $username, $firstname, $middlename, $lastname, $nameext, $phonenumber, $email, $street, $housenumber, $barangay,
            $hashedPassword, $shopname, $shoptype, $landmark, $picPath, $logoPath, $certPath, 
            'Pending',  // Status default (optional, based on your logic)
            'No'        // Customization default (optional)
        ]);

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
    <title>Seller Sign-Up</title>
</head>
<body>

<form class="form sellForm" method="POST" enctype="multipart/form-data">
    <a href="login.php" class="back">Back to Log In Page</a>    

    <div class="first">
        <h2>Full Name</h2>
        <input type="text" name="fname" class="data" placeholder="First Name" required>
        <input type="text" name="lname" class="data" placeholder="Last Name" required>
        <input type="text" name="Mname" class="data" placeholder="Middle Name"  >
        <input type="text" name="Ename" class="data" placeholder="Name Ext.">
    </div>
    <div class="first">
            <br>
                <h2>Username</h2>
                <input type="text" name="Username" id="idUsername" class="data" placeholder="Username"required value="<?= htmlspecialchars($username ?? '') ?>">
                <p class="error"><?= $usernameErr ?></p>
            </div>
    <div class="first">
        <br>
        <h2>Contact Information</h2>
        <input type="tel" name="pnumber" class="data" placeholder="Phone Number" required pattern="\d{11}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Phone number must be exactly 11 digits">
        <input type="email" name="eadrress" class="data" placeholder="Email Address" required>
    </div>

    <div class="first">
        <h2>Complete Address</h2>
        <input type="text" name="street" class="data" placeholder="Street" required>
        <input type="text" name="housenumber" class="data" placeholder="House Number">
        <select name="barangay" class="data">
            <option value="b1">Barangay 1</option>
            <option value="b2">Barangay 2</option>
            <option value="b3">Barangay 3</option>
            <option value="b4">Barangay 4</option>
        </select>
        <input type="text" name="landmark" class="data" placeholder="Landmark">
    </div>
    

    <div class="first">
        <br><br>
        <h2>Security</h2>
        <input type="password" name="password" class="data" placeholder="Password">
        <input type="password" name="cpassword" class="data" placeholder="Confirm Password"> 
    </div>

    <div class="first">
        <br><br>
            <h2>Shop Details</h2>
            <input type="text" name="shopname" class="data" placeholder="Shop Name" required>
            <select name="shoptype" class="data" required>
                <option value="">Select Shop Type</option>
                <option value="Bakery">Bakery</option>
                <option value="Custom Cakes">Custom Cakes</option>
            </select>
    </div>

    <div class="first">
        <br><br>
        <h2>Seller's Valid Identification ID</h2>
        <img src="" alt="" class="input-image" id="idPreview" onclick="document.getElementById('idUpload').click()" style="cursor:pointer;">
        <input type="file" name="idUpload" id="idUpload" accept="image/*" style="display:none;" onchange="previewImage(event, 'idPreview')" required>
    </div>

    <div class="first">
        <br><br>
        <h2>Seller's Picture</h2>
        <img src="" alt="" class="input-image" id="picPreview" onclick="document.getElementById('picUpload').click()" style="cursor:pointer;">
        <input type="file" name="picUpload" id="picUpload" accept="image/*" style="display:none;" onchange="previewImage(event, 'picPreview')" required>
    </div>

    <div class="first">
        <br><br>
        <h2>Shop's Logo</h2>
        <img src="" alt="" class="input-image" id="logoPreview" onclick="document.getElementById('logoUpload').click()" style="cursor:pointer;">
        <input type="file" name="logoUpload" id="logoUpload" accept="image/*" style="display:none;" onchange="previewImage(event, 'logoPreview')" required>
    </div>
    <div class="first">
        <br><br>
        <h2>BIR/Brgy. Cetificate</h2>
        <img src="" alt="" class="input-image" id="certPreview" onclick="document.getElementById('certUpload').click()" style="cursor:pointer;">
        <input type="file" name="certUpload" id="certUpload" accept="image/*" style="display:none;" onchange="previewImage(event, 'certPreview')" required>
    </div>

    <div class="first">
        <button type="submit" name="create" class="submit">Sign-Up</button>
    </div>
</form>

<div class="logo sellerL">
    <img src="/itcc1023/midterms/img/logo.png" alt="">
    <h3>Seller</h3>
    <h2>Sign Up</h2>
</div>

<script>
function previewImage(event, previewId) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
</body>
</html>
