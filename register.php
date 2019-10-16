<?php

	if(isset($_POST['submit'])){

		// connect to the server and select database
		$conn = mysqli_connect("localhost", "root", "","plagiarism");

		//  Get values from form in login.php
		$username = mysqli_real_escape_string($conn, $_POST["user"]);
		$password = mysqli_real_escape_string($conn, $_POST["pass"]);

		//query the database for user
		$sql = "INSERT INTO user (email, password) VALUES ('$username', '$password')";
		$result = mysqli_query($conn, $sql); 
		
		header("Location: registration.php?success=Account created");
	} else {
		header("Location: register.php");
		exit();
	}

	
        


