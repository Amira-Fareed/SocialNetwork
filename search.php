<?php
error_reporting(1);
session_start();
$currentUSerID=$_SESSION['loggedin'];

if($currentUSerID=="")
    header("Location:login.php");

$InputTxt = $_GET["txt"] ;


include 'Classes/friends.php';
include 'Classes/posts.php';
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "socialnetwork";
$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($con->connect_error) {
   die("Connection failed: " . $con->connect_error); }

if(isset($_POST['txtInput']))
{
        $InputTxt=$_POST['txtInput']; 

         header("Location:search.php?txt=".$InputTxt."");

       
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

<body>
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
                        <li role="presentation"><a href="profile.php">Timeline</a></li>
                        <li role="presentation"><a href="profile.php">Profile</a></li>
                        <li role="presentation"><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container">
        <h1>Search Result </h1>

         <div class="col-md-6" style=" overflow-y: auto">
                    <ul class="list-group" class="friendsList">
                        <li class="list-group-item"><h4><strong> Results </strong></h4> 
                        
                         <?php  
                         {
                            if($InputTxt != "")
                            {
                               $friends_list = friends::search_friends($con,$InputTxt);

                                if($friends_list !=false)
                                {

                                    for($i=0;$i<sizeof($friends_list);$i++)
                                    {
                                        $username=DB::select($con, "users", array("username"), "ID='".$friends_list[$i]
                                        ."'");
                                        echo '<a href="profile.php?id='.$friends_list[$i].'"><li class="list-group-item" user-id ='. $friends_list[$i].'> <p style="text-transform: capitalize;">'. $username[0]->username.'</p></li></a>';
                                        
                                    }
                                    return true;
                                }
                                else
                                    echo" No search result";
                            }
                        else
                            echo" No search result";
                        }   
                       ?>
                              
                        </li>
                    </ul>
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


</body>

</html>
