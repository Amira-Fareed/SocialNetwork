<?php
error_reporting(1);
include 'Classes/friends.php';
include 'Classes/posts.php';
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "socialnetwork";
$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($con->connect_error) {
   die("Connection failed: " . $con->connect_error); }

$friends=friends::get_friends($con, 1);

posts::display_posts($con, 1, $friends);

if(isset($_POST['add']))
{
	friends::add_friend($con, 1, 2);
	friends::add_friend($con, 1, 3);
	friends::add_friend($con, 1, 4);
	friends::add_friend($con, 2, 3);
	friends::add_friend($con, 2, 4);
}
if(isset($_POST['remove']))
	friends::remove_friend($con, 1, 2);

if(isset($_POST['friends']))
	friends::display_friends($con,1);
if(isset($_POST['create_post']))
{
	$body=$_POST['body'];
	posts::create_post($con, 1, $body);

}
if(isset($_POST['delete_post']))
	posts::delete_Post($con,1, 1);
	
?> 

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>friends</title>
</head>
<body>
    <h1>friend operations</h1>
    <form action="test.php" validate method="POST">
    <input type="submit" name="add" value="add friend">
    <input type="submit" name="remove" value="remove friend">
    <input type="submit" name="friends" value="show friends">
    <input type="text" name="body" placeholder="enter your post">
    <input type="submit" name="create_post" value="Create Post">
    <input type="submit" name="delete_post" value="Delete Post">
    </form>
</body>
</html>

