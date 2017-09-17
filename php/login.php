<?php
// Inside the below php, I already created an object named '$connection'
require_once '/Library/WebServer/Documents/php/class.CustomMySQLConnection.php';

// Starting Session
	session_start();

//##
// FIRST, check if user already logged in this session.
//##
	if( isset($_SESSION['login_user']) ) {
		header('Location:http://localhost/verify.htm', true, 301); // redirect to verify
		exit();
	}
// If NOT, stay remain

	//This variable is used to display error message for user
	$error = "";

// Login Button handler
	if(isset($_POST['button-login'])) {
		// Handle the username and password in forms to fix any inappropriate charset for SQL statement format
		$myusername = $connection->fixEscapeString($_POST['username']);
		$mypassword = $connection->fixEscapeString($_POST['password']);

		//Define SQL statement
		// ** 'testkey' is the key phrase used for AES ecryption **
		$query = "SELECT email,status FROM login WHERE username = '$myusername' AND password = aes_encrypt('$mypassword','$connection->encrypt_key');";
		//Execute SQL statement
		$result_set = $connection->executeSELECT($query);

		//Count the number of rows from the result set
		$count = mysqli_num_rows($result_set);

		//If result matched $myusername and $mypassword, there must be 1 row in the result
		if($count == 1) {
			//Get an array from $result_set, then save username & email to session variables
			$result = mysqli_fetch_assoc($result_set);
			$_SESSION['login_user']   = $myusername;
			$_SESSION['verify_email'] = $result['email'];
			$_SESSION['user_status']  = $result['status'];

			//Save SESSION variables for handling timeout requirements
			$_SESSION['CREATED'] = time(); // save the time when session first starts
			$_SESSION['LAST_ACTIVITY'] = $_SESSION['CREATED']; // this variable to keep track of the last time request

			// Finally, redirect to verify.htm
			header('Location:http://localhost/verify.htm', true, 301);
			exit();
		}
		// $count != 1 => username/password are NOT matched
		else {
			$error = "<span> Username or Password is incorrect !</span>";
		}
    }// End of handler
?>