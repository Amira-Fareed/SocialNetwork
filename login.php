<?php
session_start();
$Cusrrent_userID = $_SESSION['loggedin'];
if($Cusrrent_userID != "")
    header("Location:timeline.php");
error_reporting(1);
include 'Classes/DB.php';
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "socialnetwork";
$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($con->connect_error) {
   die("Connection failed: " . $con->connect_error); }


if(isset($_POST['login']))
{
		$email=$_POST['email']; 
		$password=$_POST['password'];

		$data=array("ID, email, password");
		$array=DB::select($con, "users", $data, "email='".$email."'");

	   	if($array!=false)
	   	{
	   		foreach ($array as $arr )
        	{
	        	if($arr->email===$email)
	        	{
	        		if($arr->password===$password)
				    {
				    	session_start();
				    	$_SESSION['loggedin']=$arr->ID;
				    	header("Location:timeline.php");
				    }
			        else
			    		echo"<style type=\"text/css\"> #password{border-color: red; } </style>";
                        echo'<script type="text/javascript">alert("Wrong password!");</script>';
	        	}
       		}
	   	}
        else
			echo" <style type=\"text/css\"> #email{border-color: red; } </style>"; 
            echo'<script type="text/javascript">alert("Wrong Email!");</script>';
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialNetwork</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="login-clean" style="position: absolute; top: 0; right: 0; bottom: 0; left: 0">
        <form action="login.php" validate method="post">
            <h2 class="sr-only">Login Form</h2>
            <div class="illustration"><i class="icon ion-ios-navigate"></i></div>
            <div class="form-group">
                <input class="form-control" type="email" id="email" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input class="form-control" type="password" id="password" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit" name="login" value="Login" >Log In</button>
            </div><a href="create-account.php" class="forgot">Click here to Register</a></form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>

