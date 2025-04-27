<?php
session_start();
$admin_un = "admin";
$admin_pwd = "1234";

$user_un = "CustomerUser";
$user_pwd = "5678";
$error = "";

if(isset($_REQUEST['login'])){
    $username = $_REQUEST['username'];
    $password = $_REQUEST['pwd'];

    if($admin_un == $username && $admin_pwd==$password){
        $name = $username;
        $_SESSION['name'] = $name;
        header ('location:Admin.php');
    }

    else if($user_un == $username && $user_pwd==$password){
        $cusname = $username;
        $_SESSION['cusname'] = $cusname;
        header ('location:HomePage.php');
    }

    else if($admin_un !== $username || $admin_pwd!==$password){
        $error = "Incorrect username or password.";
    }
}   

    if(!empty($_SESSION['name'])){
        header('location:Admin.php');
    }
    else if(!empty($_SESSION['cusname'])){
        header('location:HomePage.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="/itcc1023/midterms/img/logo.png" />
    <title>Your Cake - Login</title> 
    <link rel="stylesheet" href="loginstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <div class="image">
                <img src="/itcc1023/midterms/img/logo.png" alt="Logo">
            </div>
        </div>

        <form method="post"  class="form-container">
            <h1>Your Cake</h1>

            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="input-container">
                <i class='bx bxs-user icon'></i>
                <input type="text" name="username" id="username" placeholder="Username">
            </div>
            
            <div class="input-container">
                <i class='bx bxs-lock-alt icon' ></i>
                <input type="password" name="pwd" id="password" placeholder="Password">
            </div>
            <a href="#" class="forgot-password"><u>Forgot password?</u></a>
            <button class="login-btn" name="login"id="login">LOGIN</button>
            
            <div class="links">
                Sign up as a <a href="signup_customer.php"><u>customer</u></a><br> or <br> Sign up as a <a href="signup_seller.php"><u>Seller</u></a>
            </div>
        </form>
       
    </div>

    <script>
	$(function(){
		$('#login').click(function(e){

			var valid = this.form.checkValidity();

			if(valid){
				var username = $('#username').val();
				var password = $('#password').val();
			}

			e.preventDefault();

			$.ajax({
				type: 'POST',
				url: 'jslogin.php',
				data:  {username: username, password: password},
                success: function(data){
                    if ($.trim(data) === "customer") {
                        window.location.href = "signup_customer.php"; // customer page
                    } else if ($.trim(data) === "seller") {
                        window.location.href = "signup_seller.php"; // seller page
                    } else {
                        alert(data);
                    }
                }
			});

		});
	});
</script>
</body>
</html>