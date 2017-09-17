<?php
// Inside the below php file, I already created an object named '$connection'
require_once '/Library/WebServer/Documents/php/class.CustomMySQLConnection.php';
// Some functions to handle timeout requirements
require_once '/Library/WebServer/Documents/php/functions.session.php';

// Get Session
	session_start();

	checkSessionLogin(); // check existant session and status code. Also check lifetime of session.
?>

<!DOCTYPE HTML>
<html>
<head>
</head>

<body>
<h1>Login successfully!  Welcome, <i><?php echo $_SESSION['login_user']; ?></i> </h1>
<h2><a href="http://localhost/php/logout.php">Logout</h2>
</body>
</html>