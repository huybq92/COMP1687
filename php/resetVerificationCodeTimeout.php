<?php
	session_start();
	unset($_SESSION['verify_code_timeout']);
	unset($_SESSION['verify_code']); // verify.php will send another code in case this variable is unset
	header('Location:http://localhost/verify.htm', true, 301); // redirect back to verify page
	exit();
?>