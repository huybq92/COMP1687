<?php
	//Get input when user click submit and store them in seperate variables
	$name = $email = $gender = $comment = $address = NULL;
	
	//Hold the informative message for user
	$inform=NULL;
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    $name = $_POST['fullname'];
	    $email = $_POST['email'];
	    $address = $_POST['address'];
	    $comment = $_POST['comment'];
	    $gender = $_POST['gender'];
		
		//SQL commands
		$check_available_query="SELECT email FROM registration WHERE email='$email'";
		$query="INSERT INTO registration VALUES('$name','$email','$address','$comment','$gender');";
		
		//Connect to the database
		$connection = mysqli_connect("localhost","root","tr*baV4S","cos108");
		
		//Check if the input email has already been registered
		$result_set=mysqli_query($connection, $check_available_query);
		$count=mysqli_num_rows($result_set);
		if($count == 0) { // 0 means no records found in the database, the email is available to use
			//Execute query to insert data to the database
			mysqli_query($connection, $query) or die(mysqli_error($connection));
			$inform="Registration succeeded!";//Reset inform
		} else {	
			$inform="This email has already been used! Please choose another.";
		}
		//Close connection
		mysqli_close($connection);
	}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Registration Page</title>
	<link rel="stylesheet" type="text/css" href="css/style-register.css">
	<link href='http://fonts.googleapis.com/css?family=Crete+Round' rel='stylesheet' type='text/css'>
	
	<script>
		function validateForm() {
			var x = document.forms["login-form"]["username"].value;
			var y = document.forms["login-form"]["password"].value;
			if (x == "" || y == "") {
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
	<div class="container">
		<div class="register-page">
		  <div class="form">
			<form class="register-form" name="register-form" action="" onsubmit="return validateForm()" method="post">
			    <input class="textinput" type="text" name="fullname" placeholder="Full name"/>
				<input class="textinput" type="text" name="email" placeholder="Email"/>
				<input class="textinput" type="text" name="address" placeholder="Address"/>
			    <textarea name="comment" rows="4" cols="35" placeholder="Comment"></textarea>
				<input type="radio" name="gender" value="male">Male
				<input type="radio" name="gender" value="female">Female
				<br>
				<span style="color: red"><?php echo $inform; ?></span>
			    <button type="submit">Register</button>
			    <p class="message">Already registered? <a href="/login.php">Login now</a></p>
			</form>
		  </div>
		</div>
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