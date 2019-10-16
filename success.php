<?php

session_start();

	if(isset($_POST['submit'])){

		// connect to the server and select database
		$conn = mysqli_connect("localhost", "root", "","plagiarism");

		//  Get values from form in login.php
		$username = mysqli_real_escape_string($conn, $_POST["user"]);
		$password = mysqli_real_escape_string($conn, $_POST["pass"]);

		//query the database for user
		$sql = "SELECT * FROM user WHERE email = '$username' AND password = '$password'";		// check if user is available in the database
		$result = mysqli_query($conn, $sql); 
		$resultcheck = mysqli_num_rows($result);
		if($resultcheck < 1)
		{
			header("Location: login.php?invalid=Wrong Username or Password");
			exit();
		} else {
			if($row = mysqli_fetch_assoc($result)){
				$_SESSION['uid'] = $row['uid'];			// store session value for user id
				$_SESSION['user'] = $row['email'];		// store session value for user email
				$_SESSION['pass'] = $row['password'];	// store session value for user password
				date_default_timezone_set('Australia/Sydney');		// set time zone

				$sql1 = "INSERT INTO userhistory(uid, time) VALUES (?, ?) ";		// insert user history into database
				$stmt = $conn->prepare($sql1);

		        $stmt->bind_param("is", $userid,$time);

		        $userid = $row['uid'];
		        $time = date("Y-m-d H:i:s");		// produce date in this format

		        $stmt->execute();	// execute query


				header("Location: dashboard.php");	// redirect to dashboard page
			}
		}
	} else {
		header("Location: login.php?invalid=Wrong Username or Password");
		exit();
	}

	
?>

