<?php
// #### This file contains all session-related functions ####

	// This function is to regenerate session ID every 30 minutes
	// The purpose is to enhance the security by preventing fake ID attack
	function checkSessionLifetime() {
		// REFERENCE CODE: https://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes
		if (time() - $_SESSION['CREATED'] > 1800) {
		    // session started more than 30 minutes (1800 seconds) ago
		    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID. This function ONLY changes session ID, NOT the session data
		    $_SESSION['CREATED'] = time();  // update creation time
		}
	}

	function checkSessionLastActivity() {
		// REFERENCE CODE: https://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes
		if (time() - $_SESSION['LAST_ACTIVITY'] > 1800) {
		    // last request was more than 30 minutes (1800 secs) ago
		    //Redirect to logout.php to destroy session and go back to login page
		    	//header("Location:php/logout.php");
		    	//exit();
		    echo '<script type="text/javascript">',
					'window.location.replace("http://localhost/php/logout.php")',
					'</script>'; // use JavaScript to redirect
		} else {
			$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
			checkSessionLifetime(); // also check for session lifetime to regenerate session ID
		}
	}

	function checkSessionLogin() {
		if ( !isset($_SESSION['login_user']) ) {
			header('Location:http://localhost/login.htm', true, 301);
			exit();
		} else if ($_SESSION['user_status'] == '0') {
			header('Location:http://localhost/verify.htm', true, 301);
			exit();
		} else {
			checkSessionLifetime(); // otherwise stay remain and update timeout
		}
	}
?>