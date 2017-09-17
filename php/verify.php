<?php
// Class to work with MySQL Database
require_once '/Library/WebServer/Documents/php/class.CustomMySQLConnection.php';
// Class used to send verification code via email
require_once '/Library/WebServer/Documents/php/class.CustomMailer.php';
// Some functions to handle timeout requirements
require_once '/Library/WebServer/Documents/php/functions.session.php';

// Starting Session
	session_start();
	// Declare this variable here before being assigned inside IF block below
	$inform = '';

//First of all, check if session exits
	if(!isset($_SESSION['login_user'])) {
		// if NOT, go back to login
		header('Location:http://localhost/login.htm', true, 301);
		exit();
	} else {
		// Check session timeout every time the page reloads
		checkSessionLastActivity(); // examine functions.session.php for detail

		// Check if account is active or inactive.
		if ($_SESSION['user_status'] == '1') {
			//If active, continue to main.php
			header('Location: http://localhost/main.php', true, 301);
			exit();
		} else {
			// If not, stay remain in verify.htm

			// SEND MAIL
			// First check if timeout for email is over. Timeout is 30 minutes
			if (!isset($_SESSION['verify_code_timeout']) || (time() - $_SESSION['verify_code_timeout'] > 1800)) {
				// no email hasn't been sent or it was sent more than 30 minutes ago
				if (CustomMailer::emailVerifyCode()) {
					$inform = '<span>A new code has been sent to your email.</span>
							<br>
							<span>This code will be valid in 30 minutes.</span>';
				} else {
					$inform = '<span>Error: Cannot send mail!</span>';
				}
			} else {
				// if the previous code is still valid
				// ** round(): get the result of the division without remainder **
				$inform = '<span>The last code still valid in next ' . round((1800 - (time() - $_SESSION['verify_code_timeout']))/60) . ' minutes.</span>
							<br>
							<span>Recheck your email or click Resend Code to get a new one!</span>';
			}
		}
	}

//#############################################
//##### Handle the click on button-verify #####
	if(isset($_POST['button-verify'])) {

		// Firstly, check the code user entered
		if ( strcmp($_POST['verify-code'], $_SESSION['verify_code']) !== 0 ) {
			// If wrong code, inform user
			$inform = "<span> Incorrect code! </span>";
		} else {
			// If the codes match, connect to DB and CHANGE the STATUS of the account to ACTIVE
			$myusername = $_SESSION['login_user'];
			$query = "UPDATE login SET status = true WHERE username = '$myusername';"; // sql statement

			//Execute query to UPDATE record -> change account from INACTIVE -> ACTIVE
			$connection->executeCRUD($query);

			// Update user status for this session
			$_SESSION['user_status'] = true;

			// Finally, redirect to main.php after account has been verified
			echo '<script type="text/javascript">',
					'window.location.replace("http://localhost/main.php")',
					'</script>';
		}
    }// End of handler
?>