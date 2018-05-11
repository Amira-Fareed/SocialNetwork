<?php
include 'Classes/DB.php';
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "socialnetwork";
$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($con->connect_error) {
   die("Connection failed: " . $con->connect_error); }
error_reporting(1);

if($_SERVER['REQUEST_METHOD']==='POST')
{
    if(isset($_POST['register']))
    {
      $username=$_POST['username'];
      $email=$_POST['email']; 
      $password=$_POST['password'];

      $data_array=array('username'=>$username, 'email'=> $email, 'password'=> $password);
      if(DB::insert($con, "users", $data_array) ===true)
        {
          echo "you've registered successfully!";
        }

    }
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
    <div class="login-clean">
        <form method="post">
            <h2 class="sr-only">Create Account</h2>
            <div class="illustration"><i class="icon ion-ios-navigate"></i></div>
            <div class="form-group">
                <input class="form-control" id="username" type="text" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <input class="form-control" id="email" type="email" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input class="form-control" id="password" type="password" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" id="ca" type="button" data-bs-hover-animate="shake">Create Account</button>
            </div><a href="login.php" class="forgot">Already got an account? Click here!</a></form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>
