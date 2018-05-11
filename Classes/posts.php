<?php
session_start();
$current_User=$_SESSION['loggedin'];
class posts
{

	public static function create_post($con, $user_id, $body)
	{
		global $current_User;
		if($user_id==$current_User)
		{

			$data=array("user_ID"=>$user_id, "body"=>$body);
			if(DB::insert($con, "posts", $data)===true)
				{
					return "post created successfuly!";
				}
			else 
				return "Something went Wrong";
		}
		else
			return "you have to log in to create posts";	
	}

	public static function delete_post($con, $user_id, $post_id)
	{
		global $current_User;
		if($user_id==$current_User)
		{
			if(DB::delete($con, "posts", array("post_ID"=>$post_id))===true)
				{
					return "post deleted successfuly!";
					
				}
			else 
				return "Login first";
		}
		else
			return "log in to remove posts";	
	}







	public static function like_post($con, $user_id, $post_id)
	{
		global $current_User;
		if($user_id==$current_User)
		{
			$res=mysqli_query($con,"SELECT ID FROM post_likes WHERE post_ID='$post_id' AND user_ID='$user_id'");
          if($res->num_rows == 0){
                $sql = mysqli_query($con,"UPDATE posts SET likes=likes+1 WHERE post_ID= '$post_id'");
                $result = mysqli_query($con,"INSERT INTO post_likes (post_ID, user_ID) VALUES ('$post_id','$user_id')");
          }
          else{
                $sql = mysqli_query($con,"UPDATE posts SET likes=likes-1 WHERE post_ID= '$post_id'");
                $result = mysqli_query($con,"DELETE FROM post_likes WHERE post_ID= '$post_id' AND user_ID = '$user_id'");
          }
		}
	}

	public static function display_posts($con, $user_id, $friends)

	{
		global $current_User;

		foreach ($friends as $key) 
		{ 
            
			$posts=DB::select($con, "posts", array("post_ID" ,"body","posted_at", "likes"), "user_ID='".$key
			."'ORDER BY posted_at DESC");
			foreach ($posts as $post ) 
			{
				$query="SELECT username FROM posts join users ON posts.user_ID = users.ID WHERE posts.post_ID=".$post->post_ID;
				$result = mysqli_query($con, $query);
				$user_names = $result->fetch_object();
				$res=mysqli_query($con,"SELECT ID FROM post_likes WHERE post_ID='$post->post_ID' AND user_ID='$current_User'");
				if($res->num_rows == 0)
					{$color = "#c5c5c5"; $othercolor = "#eb3b60";}
				else
					{$color = "#eb3b60";  $othercolor = "#c5c5c5";}

				if($key == $current_User)
				{
					echo"<blockquote><div> <a href=\"profile.php?id=".$key."\"> <h4 style=\" color:#337ab7; width:90%; text-transform: capitalize; font-size: 88%;\"> ~ ".$user_names->username."</h4> </a> <button type=\"button\" class=\"close deletepost\" data-dismiss=\"modal\" aria-label=\"Close\" delete-postid=".$post->post_ID ."><span aria-hidden=\"true\" style=\" font-size : 60%; color:red;\">Remove</span></button></button> </div> <p>".($post->body)."</p><footer>Posted on ".($post->posted_at)." 
	                          <form validate method=\"get\" style=\" display: inline-block;\" >
	                          <button onmouseover=\"this.style.color='".$othercolor."'\" onmouseout=\"this.style.color='".$color."'\" class=\"btn btn-default\" type=\"submit\" name=\"likepostid\" value=".$post->post_ID ." style=\"color:".$color."; background-image:url(&quot;none&quot;);background-color:transparent; \"> <i class=\"glyphicon glyphicon-heart\" data-aos=\"flip-right\" ></i><span></span></button></form>

	                          <form validate method=\"get\" style=\" display: inline-block;\"> <button  class=\"btn btn-default\" type=\"submit\" name=\"likers\" value=".$post->post_ID ." style=\"color:#eb3b60;background-image:url(&quot;none&quot;);background-color:transparent;\" like-postid=".$post->post_ID ."> <i class=\"glyphicon \" data-aos=\"flip-right\"></i><span> ".($post->likes)." Likes </span></button></form>


	                            <button class=\"btn btn-default comment\" type=\"button\" style=\"color:#eb3b60;background-image:url(&quot;none&quot;);background-color:transparent;\"><i class=\"glyphicon glyphicon-flash\" style=\"color:#f9d616;\"></i><span style=\"color:#f9d616;\"> Comments</span></button></footer></blockquote>";
                }

                else
                {
                	echo"<blockquote> <a href=\"profile.php?id=".$key."\"><h4 style=\" color:#337ab7; text-transform: capitalize; font-size: 88%;\"> ~ ".$user_names->username."</h4></a><p>".($post->body)."</p><footer>Posted on ".($post->posted_at)." 
	                          
	                          <form validate method=\"get\" style=\" display: inline-block;\"> 
	                          <button onmouseover=\"this.style.color='".$othercolor."'\" onmouseout=\"this.style.color='".$color."'\" class=\"btn btn-default\" type=\"submit\" name=\"likepostid\" value=".$post->post_ID ." style=\"color:".$color.";background-image:url(&quot;none&quot;);background-color:transparent;\" like-postid=".$post->post_ID ."> <i class=\"glyphicon glyphicon-heart\" data-aos=\"flip-right\"></i><span> </span></button></form>

	                          <form validate method=\"get\" style=\" display: inline-block;\"> <button class=\"btn btn-default\" type=\"submit\" name=\"likers\" value=".$post->post_ID ." style=\"color:#eb3b60;background-image:url(&quot;none&quot;);background-color:transparent;\" > <i class=\"glyphicon \" data-aos=\"flip-right\"></i><span> ".($post->likes)." Likes </span></button></form>

	                            <button class=\"btn btn-default comment\" type=\"button\" style=\"color:#eb3b60;background-image:url(&quot;none&quot;);background-color:transparent;\"><i class=\"glyphicon glyphicon-flash\" style=\"color:#f9d616;\"></i><span style=\"color:#f9d616;\"> Comments</span></button></footer></blockquote>";

                }
			}
		}
	}

public static function likers_IDs($con ,$post_id )
{
	$likers_list = DB::select($con, "post_likes", array("user_ID"), " post_ID='".$post_id."'");
	if($likers_list != false)
		{

			for($i=0; $i<sizeof($likers_list); $i++)
			{
				$array[$i]=$likers_list[$i]->user_ID;
			}
			return $array;
		}
	else
		return false;
}


}




?>

