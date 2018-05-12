<?php

include_once("dbconnect.php");

if(isset($_POST)) 
{
	$email = $_POST['email']; 

	$sql = "SELECT email FROM users WHERE email='$email'";
	$result = $conn->query($sql);

	if($result->num_rows > 0)
		echo "Error";
	else
		echo "Ok";
}