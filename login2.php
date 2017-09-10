<!-- Source code: https://codepen.io/miroot/pen/qwIgC -->

<?php
	// Starting Session
	session_start();
	
	//Because 'error' variable is being called inside HTML code, so it must be set here
	$error="";
	
	//Check if $_SESSION['login_user'] is already set. If yes, redirect to admin.php
	if(isset($_SESSION['login_user'])) {
		header("Location:admin.php"); 
	}	
	
	/ Handle the click on button Login 
	if(isset($_POST['button-login'])) {
		
		//Database connection configuration
		$connection = mysqli_connect("127.0.0.1","root","tr*baV4S/?","test");
		
		// username and password in forms
		$myusername = mysqli_real_escape_string($connection,$_POST['username']);
		$mypassword = mysqli_real_escape_string($connection,$_POST['password']); 
		  
		$query = "SELECT email FROM login WHERE email = '$myusername' AND password = '$mypassword'";
		$result_set = mysqli_query($connection,$query);
		
		//Count the number of rows from the result set
		$count = mysqli_num_rows($result_set);

		// If result matched $myusername and $mypassword, result must contains 1 row of data			
		if($count == 1) {
			$_SESSION['login_user'] = $myusername; // Initializing Session		
			//Check if the session exists
			if(isset($_SESSION['login_user'])) {
				//close connection
				mysqli_close($connection);
				
				//header("location: http://localhost/admin.php", true, 301); // Redirecting To Other Page
				echo '<script type="text/javascript">',
					'window.location = "http://localhost/admin.php"',
					'</script>';
			}		
		}else { // if no matched results
			$error = "Your Login Name or Password is invalid";
		}	  
    }// End of 'if(isset($_POST['button-login']))'
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Admin Login</title>
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<link href='http://fonts.googleapis.com/css?family=Crete+Round' rel='stylesheet' type='text/css'>
	
	<!-- This script is used to handle the emptiness of the username/password input box -->
	<script>
		function validateForm() {
			var x = document.forms["login-form"]["username"].value;
			var y = document.forms["login-form"]["password"].value;
			if (x == "" || y == "") {
				<!-- Alert user -->
				alert("Username & password cannot be empty!");
				return false;
			}
		}
	</script>
</head>
 
<body>
	<header>
		<div style="text-align: center;">
			<a href="/"><img src = "img/tmc-logo2.png" alt = "TMC logo" /></a>
		</div>
	</header>
	
		<div class="login">
		  <div class="login-triangle"></div>
		  
		  <h2 class="login-header">Log in</h2>

		  <form class="login-container" name="login-form" action="" method="post" onsubmit="return validateForm()">
		    <p><input type="email" placeholder="Email" name="username"></p>
		    <p><input type="password" placeholder="Password" name="password"></p>
		    <span style="color: red"><?php echo $error; ?></span>
		    <p><input type="submit" value="Log in" name="button-login"></p>
		    <p class="message">Not registered? <a href="/register.php">Create an account</a></p>
		  </form>
		</div>

	<footer>
		<div class="container">
			<p>Copyright 2014, Bui Quang Huy. All rights reserved.</p>
			<p><a href="#">Terms of Service</a> I <a href="#">Privacy</a></p>
		<div class="clear"></div>
		</div>
	</footer>   
</body>
</html>