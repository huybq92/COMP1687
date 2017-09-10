<?php

// Start Session
	session_start();

	
//#############################################################	
//######### This part is to CHECK SESSION EXISTANCE.###########
//######### If no session exists, redirect to login.php #######
//######### If yes, check if account is active or inactive.####
//######### - If inactive, redirect to verify.php #############

	//Database connection configuration
	$connection = mysqli_connect("127.0.0.1","root","tr*baV4S/?","test");
	
	// Storing Session
	$user_check = $_SESSION['login_user'];
	$query = "SELECT status FROM login WHERE username='$user_check'";

	// SQL Query To Fetch Complete Information Of User
	$result_set = mysqli_query($connection, $query);
	
	//This method return an array of result
	$status = mysqli_fetch_assoc($result_set);
	
	//Get the value from the resultset
	$login_session = $status['status'];
	
	//Check if there is no session exists. If no, then redirect to the login page
	if(!isset($login_session)){
		mysqli_close($connection); // Closing Connection
		header('Location: http://localhost/login.php');
	} else {
		//Check if account is inactive. If so, redirect to the verify page
		if ($status['status'] == false) {
			header('Location: http://localhost/verify.php');
		}
	}
//#############################################################
?>

<!DOCTYPE HTML>
<html>
<head>
</head>
 
<body>
<h1>Login successfully!  Welcome, <i><?php echo $user_check; ?></i> </h1>
<h2><a href="logout.php">Logout</h2>
</body>
</html>