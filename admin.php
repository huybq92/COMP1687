<?php

	// Starting Session
	session_start();
	$inform_edit=''; //Display the message if user enters an non-existent member ID
	$inform_delete='';
	
	//Global variables for storing Member's Detail
	$id_number;
	$fullname;
	$email;
	$address;
	$comment;
	
//#############################################################	
//######### This part is to CHECK SESSION EXISTANCE.#########
//######### If no session exists, redirect to login.php #######

	//Database connection configuration
	$connection = mysqli_connect("localhost","root","tr*baV4S","cos108");
	
	// Storing Session
	$user_check=$_SESSION['login_user'];
	$query="SELECT username FROM login WHERE username='$user_check'";
	// SQL Query To Fetch Complete Information Of User
	$result_set=mysqli_query($connection, $query);
	
	//This method return an array of result
	$row = mysqli_fetch_assoc($result_set);
	
	//Get the value from the array
	$login_session = $row['username'];
	
	//Check if there is no session exists. If no, then redirect to the login page
	if(!isset($login_session)){
		mysqli_close($connection); // Closing Connection
		header('Location: http://localhost/login.php'); // Redirecting To the login page
	}
//#############################################################


//#############################################################	
//######### This part is to handle the button clicks #########
	
	//If the clicked button is button Delete
	if(isset($_POST['button-delete'])) {
		deleteMember($_POST['delete_id']);
	}
	
//####################################################################
//##################### FUNCTION DEFINITIONS #########################	
	
	// This function to get the whole table named 'registration' in the database 'cos108'
	// Then fetch the retrieved data into HTML table
	function viewMember() {
		//Database connection configuration
		$connection = mysqli_connect("localhost","root","tr*baV4S","cos108");;	
		//SQL commands
		$query="SELECT * FROM registration;";	
		//Execute query
		$result_set=mysqli_query($connection, $query);
		
		//Display result into HTML table
		echo "<table border='1'>
			<tr>
			<th>No.</th>
			<th>Full Name</th>
			<th>Email</th>
			<th>Address</th>
			<th>Comment</th>
			<th>Gender</th>
			</tr>";
		//Fetch the rows from the result_set
		while($row1 = mysqli_fetch_assoc($result_set))
		{
			echo "<tr>";
			echo "<td>" . $row1['id'] . "</td>";
			echo "<td>" . $row1['fullname'] . "</td>";
			echo "<td>" . $row1['email'] . "</td>";
			echo "<td>" . $row1['address'] . "</td>";
			echo "<td>" . $row1['comment'] . "</td>";
			echo "<td>" . $row1['gender'] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
		
		//Close connection
		mysqli_close($connection);	
	}//End of viewMember()

	//This function connects to the database and get the record of specific member
	function getMemberDetail ($id) {
		//Firstly, check the existence of the member ID
		if (!checkExistence($id)) { //If no
			$inform = "Member doesn't exist. Try again!";
		} else { // If yes, then try to get the member detail
			//Database connection configuration
			$connection = mysqli_connect("localhost","root","tr*baV4S","cos108");;	
			//SQL commands
			$query="SELECT * FROM registration WHERE id='$id';";	
			//Execute query
			$result_set=mysqli_query($connection, $query);	
			//Get the record from the result_set into an array
			//*NOTE: There must be only 1 record --> only 1 row
			$row = mysqli_fetch_assoc(result_set);	
			
			//Assign value from row to separated variables
			$GLOBALS['id_number'] = row['id'];
			$GLOBALS['fullname'] = row['fullname'];
			$GLOBALS['email'] = row['email'];
			$GLOBALS['address'] = row['address'];
			$GLOBALS['comment'] = row['comment'];
		}
	}
	
	//This function is to delete the specific record in the database
	function deleteMember ($id) {
		//Firstly, check the existence of the member ID
		if (!checkExistence($id)) { //If no
			$inform_delete = "Member doesn't exist. Try again!";
		} else { // If yes, then try to DELETE the record in the database
			//Database connection configuration
			$connection = mysqli_connect("localhost","root","tr*baV4S","cos108");;	
			//SQL commands
			$query="DELETE FROM registration WHERE id='$id';";	
			//Execute query to DELETE record
			$result_set=mysqli_query($connection, $query) or die(mysqli_error($connection));	
			//Close connection
			mysqli_close($connection);
			//Inform
			$inform_delete="Delete successfully";
			//Refresh current page after 2 seconds
			//$page = $_SERVER['PHP_SELF'];
			//$sec = "2";
			//header("Refresh: $sec; url=$page");
		}
	}
	
	// This funtion is to check the existence of specific record in the database
	function checkExistence($id) {
		//Database connection configuration
		$connection = mysqli_connect("localhost","root","tr*baV4S","cos108");;	
		//SQL commands
		$query="SELECT * FROM registration WHERE id='$id';";	
		//Execute query
		$result_set=mysqli_query($connection, $query);
		//Count the number of rows from the result set.
		$count = mysqli_num_rows($result_set);
		//Close connection after use
		mysqli_close($connection);
		//Check if there is such member in the database
		if($count == 0) { // 0 means no records found in the database
			return false; //Because there is no record
		} else {	
			return true;
		}
	}
?>


<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Admin Page</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="css/style-admin.css">
	<link href='http://fonts.googleapis.com/css?family=Crete+Round' rel='stylesheet' type='text/css'>
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>	
	<script src="script/admin.js"></script>

	<!-- This script is to handle the animation of Tabs design -->
	<script>
		$( function() {
			var selectedTab = $("#tabs").tabs('option', 'active');
			$( "#tabs" ).tabs({active: 1});
		} );
	</script>
</head>
 
<body>
	<header>
		<div class="container">
			<a href="/"><img src = "img/tmc-logo2.png" alt = "TMC logo" /></a>
			<nav>
				<ul>
					<li><a id="login_user">Welcome, <i><?php echo $login_session; ?></i></a></li>
					<li><a href="/logout.php">Logout</a></li>
				</ul>
			</nav>
		</div>
	</header>
	
	<div class="container">
		<div id="tabs">
		  <ul>
			<li><a href="#tabs-1">View Member List</a></li>
			<li><a href="#tabs-2">Edit Members</a></li>
			<li><a href="#tabs-3">Delete Members</a></li>
		  </ul>
		  <div id="tabs-1">
			<p><?php viewMember(); ?></p>
		  </div>
		  <div id="tabs-2">
		    <p>
				<form name="edit-form" action="" method="post">
					<input type="text" placeholder="Enter no." name="edit_id" size="5"/>
					<span style="color: red"><?php echo $inform_edit; ?></span>
					<button type="submit" name="button-edit">Edit</button>			  
				</form>
			</p>
			<p>
				<form name="update-form" action="" method="post">
					<input type="text" placeholder="No." name="edit_id" size="1" readonly />
					<input type="text" placeholder="Name" name="edit_name" size="15"/>
					<input type="text" placeholder="Email" name="edit_email" size="15"/>
					<input type="text" placeholder="Address" name="edit_address" size="20"/>
					<input type="text" placeholder="Comment" name="edit_comment" size="30"/><br>
					<button type="submit" name="button-update">Update</button>			  
				</form>
			</p>
			
			<p><?php viewMember(); ?></p>
		  </div>
		  <div id="tabs-3">
		  	<p>
				<form name="delete-form" action="" method="post">
					<input type="text" placeholder="Enter no." name="delete_id" size="5"/>
					<span style="color: red"><?php echo $inform_delete; ?></span>
					<button type="submit" name="button-delete">Delete</button>			  
				</form>
			</p>
			
			<!-- Display the entire table at the bottom -->
			<p><?php viewMember(); ?></p>
		  </div>
		</div>
	</div>	
		
	<div class="container">
		<footer>
			<div class="container">
				<div id="footer-info">
				<p>Copyright 2014, Bui Quang Huy. All rights reserved.</p>
				<p><a href="#">Terms of Service</a> I <a href="#">Privacy</a></p>
				</div>
			<div class="clear"></div>
			</div>
		</footer>
    </div>
</body>
</html>