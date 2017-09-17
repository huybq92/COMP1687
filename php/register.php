<?php
// Inside the below php, I already created an object named '$connection'
require 'class.CustomMySQLConnection.php';

// Starting Session
	session_start();

// FIRST, check if user already logged in this session.
	//If already, redirect to verify.htm
	if(isset($_SESSION['login_user'])) {
		header('Location: http://localhost/verify.htm', true, 301);
		exit();
	}
	// If NOT, THEN continue processing register.htm

	// Because the following variables will be assigned INSIDE below if statement
	// ==> Initialize them here as empty strings first
	$username = $password = $email = '';
	//This variable is to hold message to informing user later
	$inform = '';

	// Once the button 'CREATE ACCOUNT' is successfully clicked (JS validation is successful), this section of code will be executed
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Firstly, check if the CAPTCHA is correct.
		// If so, proceed to database processing
		if ($_POST['captcha'] == $_SESSION['digit']) {
			$username = $connection->fixEscapeString($_POST['username']);
		    $password = $connection->fixEscapeString($_POST['password']);
		    $email = $connection->fixEscapeString($_POST['email']);

			//SQL commands
			$check_available_query="SELECT username FROM login WHERE username='$username'"; // Used to check the availability of chosen username
			$create_account_query="INSERT INTO login VALUES(null, '$username', aes_encrypt('$password','$connection->encrypt_key'), '$email', false);";

			//Check if the username is already registered or not
			$result_set = $connection->executeSELECT($check_available_query);
			$count=mysqli_num_rows($result_set);

			if($count == 0) { // 0 means no records found in the database -> can use
				//Execute query to insert data to the database
				$connection->executeCRUD($create_account_query);

				// Save username & email to session variables
				$_SESSION['login_user']   = $username;
				$_SESSION['verify_email'] = $email;
				$_SESSION['user_status']  = '0'; // newly-created user is always INACTIVE

				//Save SESSION variables for handling timeout requirements
				$_SESSION['CREATED']       = time(); // save the time when session first starts
				$_SESSION['LAST_ACTIVITY'] = $_SESSION['CREATED']; // this variable to keep track of the last request time

				//Redirect to verify.htm
				echo '<script type="text/javascript">',
					'window.location = "http://localhost/verify.htm"',
					'</script>';

			} else {
				$inform="<span>This username has already been used!</span>";
			}
		}
		// If CAPTCHA is NOT correct
		else {
			$inform="<span>Incorrect CAPTCHA!</span>";
		}
	}
?>