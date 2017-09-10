<?php

	// Starting Session
	session_start();

	//Get input when user click submit and store them in seperate variables
	$username = $password = $email = NULL;
	
	//Hold the informative message for user
	$inform=NULL;

	// Once the 'CREATE ACCOUNT' is successfully clicked, this section of code will be executed
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Firstly, check if the CAPTCHA is correct. If so, proceed to database processing
		if ($_POST['captcha'] == $_SESSION['digit']) {
			$username = $_POST['username'];
		    $password = $_POST['password'];
		    $email = $_POST['email'];
			
			//SQL commands
			$check_available_query="SELECT username FROM login WHERE username='$username'"; // Used to check the availability of chosen username
			$query="INSERT INTO login VALUES(null, '$username', aes_encrypt('$password','testkey'), '$email',false);";
			
			//Connect to the database
			$connection = mysqli_connect("127.0.0.1","root","tr*baV4S/?","test");
			
			//Check if the input username has already been registered
			$result_set=mysqli_query($connection, $check_available_query);
			$count=mysqli_num_rows($result_set);
			if($count == 0) { // 0 means no records found in the database, this username is available to use
				//Execute query to insert data to the database
				mysqli_query($connection, $query) or die(mysqli_error($connection));
				$inform="<span>Registration succeeded!</span>";//Reset inform
			} else {	
				$inform="<span>This username has already been used!</span>";
			}
			//Close connection
			mysqli_close($connection);
		} 
		// If CAPTCHA is NOT correct
		else {
			$inform="<span>Incorrect CAPTCHA!</span>";
		}
	}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Registration Page</title>
	<link rel="stylesheet" type="text/css" href="css/register.css">
	<link href='http://fonts.googleapis.com/css?family=Crete+Round' rel='stylesheet' type='text/css'>
	
	<script>
		function validateForm() {
			var x = document.forms["register-form"]["username"].value;
			var y = document.forms["register-form"]["password"].value;
			var z = document.forms["register-form"]["repassword"].value;
			var w = document.forms["register-form"]["email"].value;
			var v = document.forms["register-form"]["captcha"].value;

			// Check empty fields and matched passwords
			if (x == "" || y == "" || z == "" || w == "") {
				alert("There is EMPTY field!");
				return false;
			}
			// Check if passwords are matched
			else if (y != z) {
				alert("Passwords aren't matched!")
				return false;
			}
			// Initial check for input captcha: must contain 5 digits
			else if ( !v.match(/^\d{5}$/)) {
				alert('Please enter 5 CAPTCHA digits in the box!');
				return false;
			}
			// SOURCE CODE: http://www.the-art-of-web.com/php/captcha/
			//else if(!form.captcha.value.match(/^\d{5}$/)) {
      		//	alert('Please enter the CAPTCHA digits in the box provided');
      		//	form.captcha.focus();
      		//	return false;
    		//}
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
		<div class="register">

		<!-- Form -->
		  <form name="register-form" action="" method="post" onsubmit="return validateForm()">
		  	<span></span>
		    <p><input type="textinput" placeholder="username" name="username" maxlength="50"></p>
		    <p><input type="password" placeholder="password" name="password" maxlength="50"></p>
		    <p><input type="password" placeholder="re-type password" name="repassword" maxlength="50"></p>
		    <p><input type="email" placeholder="email" name="email" maxlength="100" required></p>		
		    
		    <!-- captcha -->
		    <!-- SOURCE CODE: http://www.the-art-of-web.com/php/captcha/ -->
		    <p class="captcha">
		    	<img src="captcha.php" width="120" height="30" border="1" alt="CAPTCHA">
				<input type="textinput" maxlength="5" name="captcha" value="">		
				<small>copy the digits from the image into above box</small>
			</p>

			<!-- submit button-->
			<?php echo $inform; ?>
		    <p><input type="submit" value="CREATE ACCOUNT" name="button-register"></button><p>
		    <p class="message">Already registered? <a href="/login.php">Login now</a></p>
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