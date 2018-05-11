<?php
error_reporting(1);
session_start();
$currentUSerID=$_SESSION['loggedin'];

if($currentUSerID=="")
    header("Location:login.php");

include 'Classes/friends.php';
include 'Classes/posts.php';
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "socialnetwork";
$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($con->connect_error) {
   die("Connection failed: " . $con->connect_error); }




$friends=friends::get_friends($con, $currentUSerID);

array_push($friends, $currentUSerID) ;


if(isset($_POST['txtInput']))
{
        $InputTxt=$_POST['txtInput']; 

         header("Location:search.php?txt=".$InputTxt."");

       
}


if(isset($_GET['likepostid'])) {
    $like_postId= $_GET['likepostid'] ;
    posts::like_post($con,$currentUSerID,$like_postId);
}
    
if(isset($_GET['likers'])) 
    {
        echo "<div onload = \"showlikersModal()\"";
           echo'<div class="modal fade" id="likers" role="dialog" tabindex="-1" style="padding-top:100px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Likers</h4></div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto">';
                    $like_postId= $_GET['likers'] ;
                    $likers = posts::likers_IDs($con,$like_postId);
                    if($likers != false)
                    {
                        
                        for($i=0;$i<sizeof($likers);$i++)
                        {
                            $username=DB::select($con, "users", array("username"), "ID='".$likers[$i]
                            ."'");
                            echo '<a href="profile.php?id='.$likers[$i].'"><li class="list-group-item" user-id ='. $likers[$i].'> <p style="text-transform: capitalize;">'. $username[0]->username.'</p></li></a>';
                            
                        }


                    }

                echo' </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>';


    }

    
?> 

<?php  
        $delete_postId= $_GET['deletepostid'] ;
        $message = posts::delete_post($con,$currentUSerID,$delete_postId);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Dark.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/untitled.css">
</head>

<body onload="showlikersModal()">
    <header class="hidden-sm hidden-md hidden-lg">
        <div class="searchbox">
            <form validate method="post">
                <h1 class="text-left">Social Network</h1>
                <div class="searchbox"><i class="glyphicon glyphicon-search"></i>
                    <input class="form-control sbox" name="txtInput"  >
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
                            <input class="form-control sbox"  name="txtInput" type="text">
                            <ul class="list-group autocomplete" style="position:absolute;width:100%; z-index:100">
                            </ul>
                        </div>
                    </form>
                    <ul class="nav navbar-nav hidden-md hidden-lg navbar-right">
                        <li class="active" role="presentation"><a href="timeline.php">Timeline</a></li>
                        <li class="dropdown open"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" href="#">User <span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                <li role="presentation"><a href="profile.php">My Profile</a></li>
                                <li class="divider" role="presentation"></li>
                                <li role="presentation"><a href="logout.php">Logout </a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav hidden-xs hidden-sm navbar-right">
                        <li class="active" role="presentation"><a href="profile.php">Timeline</a></li>
                        <li role="presentation"><a href="profile.php">Profile</a></li>
                        <li role="presentation"><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container" style="margin-bottom: 60px;">
        <h1>Timeline </h1>
        <div class="timelineposts" >

            <?php posts::display_posts($con, $currentUSerID, $friends); ?>

        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" style="padding-top:100px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Comments</h4></div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto">
                    <p>The content of your modal.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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
        $('[delete-postid]').click(function() {
                 var buttonid = $(this).attr('delete-postid');
                 window.location.replace('timeline.php?deletepostid='+ buttonid);
                             
        });

        function showlikersModal() {
                            $('#likers').modal('show');} 

        
    </script>



</body>

</html>
