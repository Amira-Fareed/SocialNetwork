<?php

include_once("dbconnect.php");

if(isset($_POST)) 
{
	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = $_POST ['password'];

	$sql = "INSERT INTO users (username, password,email)
	        VALUES ('$name','$password','$email')";
	$result = $conn->query($sql);

	if(!$result)
		echo mysqli_error($conn);
	else
	{
		$_SESSION['registeredSuccessfully'] = true;
		echo "ok";
	}
}