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


if(isset($_POST['likepostid'])) {
    $like_postId= $_POST['likepostid'] ;
    posts::like_post($con,$currentUSerID,$like_postId);
}

if(isset($_POST['comment']))
{

    echo posts::createComment($con,$_POST['comment_body'],$_POST['comment'], $currentUSerID);
}

if(isset($_POST['comments']))
{
    $post_id= $_POST['comments'] ;
     $Comments = posts::display_comments($con,$post_id);
    echo "<div onload = \"showModal()\"";
           echo'<div class="modal fade" id="Modal" role="dialog" tabindex="-1" style="padding-top:100px; "overflow:scroll; height:400px;"" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Comments</h4></div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto">
';
                    
                    if($Comments != false)
                    {
                       foreach ($Comments as $Comment) 
                        {
                            if($Comment['ID']==$currentUSerID)
                            {
                                echo "<blockquote><div> <a href=\"profile.php?id=".$Comment['ID']."\"> <h4 style=\" color:#337ab7; width:90%; text-transform: capitalize; font-size: 88%;\"> ~ ".$Comment['username']."</h4> </a> <button type=\"button\" class=\"close deletepost\" data-dismiss=\"modal\" aria-label=\"Close\" delete-commentid=".$Comment['comment_id'] ." postID = ".$post_id."><span aria-hidden=\"true\" style=\" font-size : 60%; color:red;\">Remove</span></button></button> </div> <p>".($Comment['body'])."</p><footer>Posted on ".
                                $Comment['posted_at']."</blockquote><br> ";
                            }

                            else
                            {
                                echo "<blockquote><div> <a href=\"profile.php?id=".$Comment['ID']."\"> <h4 style=\" color:#337ab7; width:90%; text-transform: capitalize; font-size: 88%;\"> ~ ".$Comment['username']."</h4> </a> </div> <p>".($Comment['body'])."</p><footer>Posted on ".
                                $Comment['posted_at']."</blockquote><br> ";
                            }
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
    
if(isset($_POST['likers'])) 
    {
        echo "<div onload = \"showModal()\"";
           echo'<div class="modal fade" id="Modal" role="dialog" tabindex="-1" style="padding-top:100px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Likers</h4></div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto">';
                    $like_postId= $_POST['likers'] ;
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

if(isset( $_GET['deletepostid']))
{ 
    $delete_postId= $_GET['deletepostid'] ;
    $message = posts::delete_post($con,$currentUSerID,$delete_postId);
}

if(isset($_GET['deletecommentid']))
{
    $delete_commentId= $_GET['deletecommentid'] ;
    $commentPostID = $_GET['postID'] ;
    $message = posts::deleteComment ($con, $delete_commentId ,$commentPostID);
}
?>

<!DOCTYPE html>
<html > 

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
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/css/emoji.css" rel="stylesheet">
</head>
<body onload="showModal()">
<?php include "header.php";?>
    <div  class="col-md-8" class="container" style="margin-bottom: 60px; margin-left: 10%;">
        <h1>Timeline </h1>
        <div class="timelineposts" >

            <?php posts::display_posts($con, $currentUSerID, $friends,0); ?>

        </div>
    </div>
    <!-- Comments -->    
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

         $('[delete-commentid]').click(function() {
                 var buttonid = $(this).attr('delete-commentid');
                 var postid = $(this).attr('postID');
                 window.location.replace('timeline.php?deletecommentid='+ buttonid +'&&postID='+postid);
                             
        });

        function showModal() 



        {
            $('#Modal').modal('show');
        } 
       
        
    </script>

            <!-- Begin emoji-picker JavaScript -->
    <script src="lib/js/config.js"></script>
    <script src="lib/js/util.js"></script>
    <script src="lib/js/jquery.emojiarea.js"></script>
    <script src="lib/js/emoji-picker.js"></script>
    <!-- End emoji-picker JavaScript -->

    <script>
      $(function() {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
          emojiable_selector: '[data-emojiable=true]',
          assetsPath: 'lib/img/',
          popupButtonClasses: 'fa fa-smile-o'
        });
        // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
        // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
        // It can be called as many times as necessary; previously converted input fields will not be converted again
        window.emojiPicker.discover();
      });
    </script>

</body>

</html>
