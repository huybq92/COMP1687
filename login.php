<?php
	// Starting Session
	session_start();
	
	//Because 'error' variable is being called inside HTML code, so it must be set here first
	$error="";
	
	//Check if $_SESSION['login_user'] is already set. If yes, redirect to main.php
	if(isset($_SESSION['login_user'])) {
		header("Location:verify.php"); 
	}	
	
	// Handle the click on button Login
	if(isset($_POST['button-login'])) {
		
		//Database connection configuration
		$connection = mysqli_connect("127.0.0.1","root","tr*baV4S/?","test");
		
		// username and password in forms
		$myusername = mysqli_real_escape_string($connection,$_POST['username']);
		$mypassword = mysqli_real_escape_string($connection,$_POST['password']); 
		  
		$query = "SELECT status FROM login WHERE username = '$myusername' AND password = aes_encrypt('$mypassword','testkey');";
		$result_set = mysqli_query($connection,$query);
		
		//Count the number of rows from the result set
		$count = mysqli_num_rows($result_set);

		// If result matched $myusername and $mypassword, there must be 1 row			
		if($count == 1) {
			// Initializing Session
			$_SESSION['login_user'] = $myusername;		

			// Close MySQL connection
			mysqli_close($connection);
			
			// REDIRECT TO verify.php
			//header("location: http://localhost/main.php", true, 301); 
			echo '<script type="text/javascript">',
					'window.location = "http://localhost/verify.php"',
					'</script>';
		} 

		// $count != 1 => no match
		else {
			$error = "<span> Email or Password is incorrect !</span>";
		}	  
    }// End of 'if(isset($_POST['button-login']))'
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Login Page</title>
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<link href='http://fonts.googleapis.com/css?family=Crete+Round' rel='stylesheet' type='text/css'>
	
	<!-- This script is used to handle the emptiness of the username/password input box -->
	<script>
		function validateForm() {
			var x = document.forms["login-form"]["username"].value;
			var y = document.forms["login-form"]["password"].value;
			if (x == "" || y == "") {
				<!-- Alert user -->
				alert("Email & password cannot be empty!");
				return false;
			}
		}
	</script>
</head>
 
<body>
	<header>
		<div class="container">
			<a href="/"><img src = "img/tmc-logo2.png" alt = "TMC logo" /></a>
		</div>
	</header>	

	<div class="container">
		<div class="login">
			<form name="login-form" action="" method="post" onsubmit="return validateForm()">	  	
			    <p><input type="textinput" placeholder="username" name="username" maxlength="50"></p>
			    <p><input type="password" placeholder="password" name="password" maxlength="50"></p>
			    <?php echo $error; ?>
			    <p><input type="submit" value="LOG IN" name="button-login"><p>
			    <p class="message">Not registered? <a href="/register.php">Create an account</a></p>
			</form>
		</div>
	</div>

	<footer>
		<div class="container">
			<p><small>Copyright 2017, Bui Quang Huy. All rights reserved.</small></p>
			<p><small><a href="#">Terms of Service</a> I <a href="#">Privacy</a></small></p>
		<div class="clear"></div>
		</div>
	</footer>   
</body>
</html>

<!-- Reference source code: https://codepen.io/miroot/pen/qwIgC  -->