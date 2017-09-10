<?php
	// Starting Session
	session_start();
	
	//Because 'error' variable is being called inside HTML code, so it must be set here first
	$error="";
	
	//Check if $_SESSION['login_user'] is already set. If NO, redirect backwards to login.php
	if(!isset($_SESSION['login_user'])) {
		header("Location:login.php"); 
	} 
	// If session exists, check if the account is ACTIVE or INACTIVE
	else {
		//Database connection configuration
		$connection = mysqli_connect("127.0.0.1","root","tr*baV4S/?","test");
		
		// Storing Session
		$user_check = $_SESSION['login_user'];
		$query = "SELECT status, email FROM login WHERE username='$user_check'";

		// SQL Query To Fetch Complete Information Of User
		$result_set = mysqli_query($connection, $query);
		
		//This method return an array of result
		$result = mysqli_fetch_assoc($result_set);
		
		//Get the value from the resultset
		$login_session = $result['status'];
		
		//Check if there is no session exists. If no, then redirect to the login page
		if(!isset($login_session)){
			mysqli_close($connection);
			header('Location: http://localhost/login.php');
		} else {
			// Check if account is active or inactive. If active, redirect to the main.php
			if ($result['status'] == true) {
				header('Location: http://localhost/main.php');
			} 
			// If the account is INACTIVE, generate a random 5-digit code and send to email
			else {
				// Retrieve email address from the resultset
				$verify_email = $result['email'];

				// Generate random 5-digit code
				//$verify_code = '';
				//for ($x = 0; $x < 5; $x += 1) {
	    		//	$verify_code .= rand(0, 9);
	  			//}
	  			// record verify_code in session variable
	  			//$_SESSION['verify-code'] = $verify_code;

				$_SESSION['verify-code'] = '11111';
	  			//
	  			// SEND MAIL SECTION............
	  			//
			}
		}
	}
	
	//#############################################
	//##### Handle the click on button Verify #####
	if(isset($_POST['button-verify'])) {
		
		// Firstly, check the entered code
		// If code doesn't match, inform user
		if ($_POST['verify-code'] != $_SESSION['verify-code']) {
			$error = "<span> Incorrect code! </span>";
		} else {
			//Database connection configuration
			$connection = mysqli_connect("127.0.0.1","root","tr*baV4S/?","test");
			
			// session user
			$myusername = $_SESSION['login_user'];
			  
			$query = "UPDATE login SET status = true WHERE username = '$myusername';";

			//Execute query to UPDATE record -> change account to ACTIVE status
			$result_set=mysqli_query($connection, $query) or die(mysqli_error($connection));
			
			//Close connection
			mysqli_close($connection);

			// Finally, redirect to main.php
			echo '<script type="text/javascript">',
					'window.location = "http://localhost/main.php"',
					'</script>';
		}  
    }// End of 'if(isset($_POST['button-verify']))'
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Verification Page</title>
	<link rel="stylesheet" type="text/css" href="css/verify.css">
	<link href='http://fonts.googleapis.com/css?family=Crete+Round' rel='stylesheet' type='text/css'>
	
	<!-- This script is used to handle the emptiness of the verify-code box -->
	<script>
		function validateForm() {
			var x = document.forms["verify-form"]["verify-code"].value;
			if ( !x.match(/^\d{5}$/)) {
				alert('Please enter 5-digit code in the box!');
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
		<div class="verify">
			<form name="verify-form" action="" method="post" onsubmit="return validateForm()">
				<p class="message" style="text-align: center;"><strong>YOU NEED TO VERIFY YOUR EMAIL FIRST!</strong></p>
				<p class="message"> We sent a verification code to your email !</p>	  	
			    <p><input type="textinput" placeholder="enter the code here" name="verify-code" maxlength="5"></p>
			    <?php echo $error; ?>
			    <p><input type="submit" value="VERIFY" name="button-verify"><p>
			    <p class="message">Come back to <a href="/logout.php">Login Page</a></p>
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