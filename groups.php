<?php
error_reporting(1);
session_start();
$currentUSerID=$_SESSION['loggedin'];

if($currentUSerID=="")
    header("Location:login.php");

$otherUserId= $_GET['id'] ;
include 'Classes/friends.php';
include 'Classes/posts.php';
include 'Classes/group.php';
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "socialnetwork";
$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($con->connect_error) {
   die("Connection failed: " . $con->connect_error); }


if(isset($_POST['Creategroup']))
{
    $groupname=$_POST['groupname'];
    $message = group::create_group($con ,$currentUSerID,$groupname);
    echo '<script  type="text/javascript"> function showMessage() {confirm("'.$message.'");} showMessage();</script>';

}
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

</body>
</html>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Dark.css">
    <link rel="stylesheet" href="assets/css/Highlight-Clean.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/untitled.css">
</head>

<body onload="showModal()">

    <header class="hidden-sm hidden-md hidden-lg">
        <div class="searchbox">
            <form validate method="post">
                <h1 class="text-left">Social Network</h1>
                <div class="searchbox"><i class="glyphicon glyphicon-search"></i>
                    <input class="form-control sbox" type="text"  name="txtInput">
                    <ul class="list-group autocomplete" style="position:absolute;width:100%; z-index: 100">
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">MENU <span class="caret"></span></button>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        <li role="presentation"><a href="profile.php">My Profile</a></li>
                        <li role="presentation"><a href="timeline.php">Timeline</a></li>
                        <li class="divider" role="presentation"></li>
                        <li role="presentation"><a href="logout.php">Logout </a></li>
                    </ul>
                </div>
            </form>
        </div>
        <hr>
    </header>
    <div>
        <nav class="navbar navbar-default hidden-xs navigation-clean">
            <div class="container">
                <div class="navbar-header"><a class="navbar-brand navbar-link" href="timeline.php"><i class="icon ion-ios-navigate"></i></a>
                    <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                </div>
                <div class="collapse navbar-collapse" id="navcol-1">
                    <form class="navbar-form navbar-left" validate method="post">
                        <div class="searchbox"><i class="glyphicon glyphicon-search"></i>
                            <input class="form-control sbox" type="text"  name="txtInput">
                            <ul class="list-group autocomplete" style="position:absolute;width:100%; z-index:100">
                            </ul>
                        </div>
                    </form>
                    <ul class="nav navbar-nav hidden-md hidden-lg navbar-right">
                        <li  role="presentation"><a href="timeline.php">Timeline</a></li>
                        <li class="dropdown open"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" href="#">User <span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                <li role="presentation"><a href="profile.php">My Profile</a></li>
                                <li class="divider" role="presentation"></li>
                                <li role="presentation"><a href="logout.php">Logout </a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav hidden-xs hidden-sm navbar-right">
                        <li role="presentation"><a href="timeline.php">Timeline</a></li>
                        <?php 
                            if ($currentUSerID != $otherUserId)
                                echo'<li role="presentation"><a href="profile.php">Profile</a></li>';

                            else
                                echo'<li class="active" role="presentation"><a href="profile.php">Profile</a></li>';
                        ?>
                        
                        <li role="presentation"><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container" style="margin-bottom: 60px; margin-top: 20px;">

            <div class="row">


            <div class="col-md-6">

                    <button class="btn btn-default" type="button" style="width:100%;background-image:url(&quot;none&quot;);background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;" onclick="showNewGroupModal()">NEW GROUP</button>
                    <ul class="list-group"></ul>


                    <ul class="list-group">
                            <div class="timelineposts">

                            </div>
                    </ul>
                </div>
                
        </div>


    <div class="modal fade" id="newgroup" role="dialog" tabindex="-1" style="padding-top:100px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                    <h4 class="modal-title">Group Name</h4>
                </div>
                <div style="max-height: 400px; overflow-y: auto">
                        <form action="groups.php" method="post" enctype="multipart/form-data">
                                <textarea name="groupname"  rows="3" cols="80"></textarea>


                
                <div class="modal-footer">
                    <input type="submit" name="Creategroup" value="Post" class="btn btn-default" type="button" style="background-image:url(&quot;none&quot;);background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button></div>

                    </form>
                    
                </div>
            </div>
        </div>
    </div>

<div class="col-md-6">
    <ul class="list-group" class="groupList">
                        <li class="list-group-item"><span><strong>My Groups</strong></span> 
                        
                        <br>
                         <?php  
                            group::display_groups($con, $currentUSerID);
                         ?>
                              
                        </li>
    </ul>
</div>
    <div class="footer-dark navbar-fixed-bottom" style="position: fixed;">
        <footer>
            <div class="container">
                <p class="copyright">Social Network© 2018</p>
            </div>
        </footer>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-animation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>

        <script type="text/javascript">


        function showNewGroupModal() {
                $('#newgroup').modal('show')
        }


       
        function showModal() 
        {
            $('#Modal').modal('show');
        } 
    </script>
</body>

</html>
