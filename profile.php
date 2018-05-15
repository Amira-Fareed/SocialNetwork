<?php
error_reporting(1);
session_start();
$currentUSerID=$_SESSION['loggedin'];

if($currentUSerID=="")
    header("Location:login.php");

$otherUserId= $_GET['id'] ;
include 'Classes/friends.php';
include 'Classes/posts.php';
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "socialnetwork";
$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($con->connect_error) {
   die("Connection failed: " . $con->connect_error); }

if($otherUserId == "" )
{
    $otherUserId = $currentUSerID;
}


$currentUserName=DB::select($con, "users", array("username"), "ID='".$currentUSerID."'");
$currentUserName=$currentUserName[0]->username;

$otherUserName=DB::select($con, "users", array("username"), "ID='".$otherUserId."'");
$otherUserName=$otherUserName[0]->username;



if(isset($_POST['Add']))
{
    friends::add_friend($con, $currentUSerID, $otherUserId);
}

if(isset($_POST['remove']))
{
    friends::remove_friend($con, $currentUSerID, $otherUserId);
}




$friends=friends::get_friends($con, $currentUSerID);






if(isset($_POST['Createpost']))
{
    $body=$_POST['postbody'];
    $message = posts::create_post($con, $currentUSerID, $body,0);
    echo '<script  type="text/javascript"> function showMessage() {confirm("'.$message.'");} showMessage();</script>';

}


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
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $otherUserName; ?></title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Dark.css">
    <link rel="stylesheet" href="assets/css/Highlight-Clean.css">
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
    <div class="container" style="margin-bottom: 60px;">
        <h1 style="text-transform: capitalize;"><?php echo $otherUserName; ?>'s Profile </h1></div>
    <div>
        <div class="container" style="margin-bottom: 60px;">
            <div class="row">


                <div class="col-md-3" style="max-height: 370px; overflow-y: auto">

                    <div >
                        <?php
                            if($currentUSerID != $otherUserId)
                            {
                                $check = 1;

                                foreach ($friends as $key ) 
                                {
                                    if($key == $otherUserId)
                                        $check = 0;
                                }

                                if($check == 1)
                                {
                                    echo ' 
                                    <form validate method="post"><button class="btn btn-default" validate method="post" action="profile.php"  type="submit" name="Add" value="Add" style="width:100%;background-image:url(&quot;none&quot;);background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;" id="Add"  >Add Friend</button>
                                    <ul class="list-group"></ul> <form>';
                                }

                                else
                                {
                                    echo ' 
                                    <form validate method="post" ><button class="btn btn-default" type="submit" name="remove" value="remove"  style="width:100%;background-image:url(&quot;none&quot;);background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;" >Remove Friend</button>
                                    <ul class="list-group"></ul> </form>';
                                }

                            }
                        ?>
                    </div>

                    <ul class="list-group" class="friendsList">
                        <li class="list-group-item"><span><strong>My Friends</strong></span> 
                        
                        <br>
                         <?php  
                            friends::display_friends($con, $otherUserId);
                         ?>
                              
                        </li>
                    </ul>
                </div>

            <div class="col-md-7">
                <?php
                if($currentUSerID == $otherUserId)
                {
                    echo ' 
                    <button class="btn btn-default" type="button" style="width:100%;background-image:url(&quot;none&quot;);background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;" onclick="showNewPostModal()">NEW POST</button>
                    <ul class="list-group"></ul>
                ';
                }
                ?>


                    <ul class="list-group">
                            <div class="timelineposts">
                            <?php posts::display_posts($con, $otherUserId, array($otherUserId),0); ?>

                            </div>
                    </ul>
                </div>
                
            </div>
        </div>
    </div>

    <div class="modal fade" id="newpost" role="dialog" tabindex="-1" style="padding-top:100px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                    <h4 class="modal-title">New Post</h4>
                </div>
            
                        <form action="profile.php?" method="post" enctype="multipart/form-data">
                          <div style="height: 250px; max-height: 400px; overflow-y: auto">      
                                
                                    <p style="width: 98%;margin: 0 auto;" class="lead emoji-picker-container">
                                      <textarea style="height: 150px; " name="postbody" class="form-control textarea-control" rows="8" cols="75" placeholder="What's on your mind ?" data-emojiable="true" data-emoji-input="unicode"></textarea>
                                    </p>
            
                           </div>     

                <div class="modal-footer">
                    <input type="submit" name="Createpost" value="Post" class="btn btn-default" type="button" style="background-image:url(&quot;none&quot;);background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                    </form>
                
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


        function showNewPostModal() {
                $('#newpost').modal('show')
        }


        $('[delete-postid]').click(function() {
                 var buttonid = $(this).attr('delete-postid');
                 window.location.replace('profile.php?deletepostid='+ buttonid);
                  });

        $('[delete-commentid]').click(function() {
                 var buttonid = $(this).attr('delete-commentid');
                 var postid = $(this).attr('postID');
                 window.location.replace('profile.php?deletecommentid='+ buttonid +'&&postID='+postid);
                             
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
