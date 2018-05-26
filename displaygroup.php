<?php
error_reporting(1);
session_start();
$currentUSerID=$_SESSION['loggedin'];

if($currentUSerID=="")
    header("Location:login.php");

$groupId= $_GET['id'] ;
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

if($groupId == "" )
{
    header("Location:groups.php");
}

$group_name = DB::select($con,"groups",array("name"),"groupID='".$groupId."'");
$group_name = $group_name[0]->name;




if(isset( $_POST['deleteuser']))
{ 
    $delete_userId= $_POST['deleteuser'] ;
    $message = group::delete_user($con,$currentUSerID,$delete_userId,$groupId);
    echo '<script  type="text/javascript">confirm("'.$message.'");</script>';
}

if(isset( $_POST['adduser']))
{ 
    $add_userId= $_POST['adduser'] ;
    $message = group::add_user($con,$currentUSerID,$add_userId,$groupId);
    echo '<script  type="text/javascript">confirm("'.$message.'");</script>';
}

$admin_ID = DB::select($con,"groups",array("adminID"),"groupID='".$groupId."'");
$admin_ID = $admin_ID[0]->adminID;
$group_users=group::get_users($con, $groupId);

if(isset($_POST['RemoveGroup']))
{
    $message = group::delete_group($con,$currentUSerID,$groupId);
    echo '<script  type="text/javascript">confirm("'.$message.'");</script>';
    header("Location:groups.php");
}

/////////////checking if user is a member in the group ////////////
$check =0;
for($i=0;$i<sizeof($group_users);$i++)
{
    if($group_users[$i] == $currentUSerID)
        $check = 1;
}

if($check == 0)
    header("Location:groups.php");

/////////////////////////////////////////

if(isset($_POST['Createpost']))
{
    $body=$_POST['postbody'];
    $message = posts::create_post($con, $currentUSerID, $body,$groupId);
    echo '<script  type="text/javascript">confirm("'.$message.'");</script>';

}

if(isset($_POST['txtInput']))
{
    $InputTxt=$_POST['txtInput'];
    header("Location:search.php?txt=".$InputTxt."");       
}

if(isset($_POST['Add'])) 
{
    group::display_users($con,$currentUSerID,$groupId);

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
                                echo "<blockquote><div> <a href=\"profile.php?id=".$Comment['ID']."\"> <h4 style=\" color:#337ab7; width:90%; text-transform: capitalize; font-size: 88%;\"> ~ ".$Comment['username']."</h4> </a> <button type=\"button\" class=\"close \" data-dismiss=\"modal\" aria-label=\"Close\" groupid=".$groupId." delete-commentid=".$Comment['comment_id'] ." postID = ".$post_id."><span aria-hidden=\"true\" style=\" font-size : 60%; color:red;\">Remove</span></button></button> </div> <p>".($Comment['body'])."</p><footer>Posted on ".
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

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $group_name?></title>
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

        <h1 style="text-transform: capitalize;"><?php echo $group_name ?> </h1>
    <div>
        <div class="container" style="margin-bottom: 60px;">
            <div class="row">


                <div class="col-md-3" style="max-height: 370px; overflow-y: auto">

                    <div >
                        <?php
                            if($currentUSerID == $admin_ID)
                            {
                                
                                    echo ' 
                                    <button class="btn btn-default" validate method="post" action="profile.php"  type="submit" name="Add" value="Add" style="width:100%;background-image:url(&quot;none&quot;);background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;" id="Add" onclick="showUsersModal()" >Add User</button>
                                    <ul class="list-group"></ul>';
                             

                            }
                        ?>
                    </div>


                    <ul class="list-group" class="friendsList">
                        <li class="list-group-item"><span><strong>Group Members</strong></span> 
                        
                        <br><br>
                         <?php  
                            for($i=0;$i<sizeof($group_users);$i++)
                                    {
                                        $username=DB::select($con, "users", array("username"), "ID='".$group_users[$i]
                                        ."'");
                                        echo '<form validate method ="post"><li class="list-group-item" user-id ='. $group_users[$i].'><a href="profile.php?id='.$group_users[$i].'"> <span style="text-transform: capitalize;">'. $username[0]->username.'</span></a>';
                                        if($group_users[$i]!=$admin_ID)
                                        {
                                            if($currentUSerID== $admin_ID)
                                            {
                                                echo'<button style="float:right;" name="deleteuser" value ="'.$group_users[$i].'">Remove</button>';
                                            }
                                        }
                                        echo'</li></form>';

                                        
                                    }
                         ?>
                              
                        </li>
                    </ul>

                    <div >
                        <?php
                            if($currentUSerID == $admin_ID)
                            {
                                
                                    echo ' 
                                    <form method="post"><button class="btn btn-default" validate method="post" action="profile.php"  type="submit" name="RemoveGroup" value="RemoveGroup" style="width:100%;background-image:url(&quot;none&quot;);background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;" id="RemoveGroup" >Remove Group</button></form>';
                             

                            }
                        ?>
                    </div>
                </div>

<!--////////////////////////////////////// Posts //////////////////////////////////////-->
            <div class="col-md-7">
                <?php
               
                    echo ' 
                    <button class="btn btn-default" type="button" style="width:100%;background-image:url(&quot;none&quot;);background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;" onclick="showNewPostModal()">NEW POST</button>
                    <ul class="list-group"></ul>
                ';
                ?>
                    <ul class="list-group">
                            <div class="timelineposts">
                            <?php group::display_posts($con,$groupId); ?>

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
            
                        <form  method="post" enctype="multipart/form-data">
                          <div style="height: 250px; max-height: 400px; overflow-y: auto">      
                                
                                    <p style="width: 98%;margin: 0 auto;" class="lead emoji-picker-container">
                                      <textarea style="height: 150px; " name="postbody" class="form-control textarea-control" rows="8" cols="75" placeholder="What's on your mind ?" data-emojiable="true" data-emoji-input="unicode"></textarea>
                                    </p>
            
                           </div>     

                <div class="modal-footer">
                    <input type="submit" name="Createpost" value="Post" class="btn btn-default" type="button" style="background-image:url(&quot;none&quot;);background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;"><button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                    </div>
                    
                    </form>
                
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_users" role="dialog" tabindex="-1" style="padding-top:100px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                    <h4 class="modal-title">ADD USERS</h4>
                </div>
                <div style="max-height: 400px; overflow-y: auto">
                        
                <?php group::display_users($con,$currentUSerID,$groupId);?>

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


        function showNewPostModal() {
                $('#newpost').modal('show')
        }

        function showUsersModal() {
                $('#add_users').modal('show')
        }

        function showModal() 
        {
            $('#Modal').modal('show');
        }

        $('[delete-postid]').click(function() {
                 var buttonid = $(this).attr('delete-postid');
                 var grpID = $(this).attr('groupid');
                 window.location.replace('displaygroup.php?id='+ grpID +'&&deletepostid='+ buttonid);
        });

        $('[delete-commentid]').click(function() {
                 var buttonid = $(this).attr('delete-commentid');
                 var postid = $(this).attr('postID');
                 var grpID = $(this).attr('groupid');
                 window.location.replace('displaygroup.php?id='+grpID+'&&deletecommentid='+ buttonid +'&&postID='+postid);
                             
        });
       
         
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
