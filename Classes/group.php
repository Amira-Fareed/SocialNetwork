<?php  

session_start();
$current_User=$_SESSION['loggedin'];

class group 
{
	public static function create_group($con ,$user_id,$group_name)
	{
		$data = array("name"=>$group_name,"adminID"=>$user_id);
		if(DB::insert($con,"groups",$data)==true)
		{
			$sql = "SELECT groupID FROM groups ORDER BY groupID DESC LIMIT 1";
			$res = mysqli_query($con,$sql);
			if($res->num_rows != 0){
				$lastid = $res->fetch_object();
				$lastid = $lastid->groupID;
				DB::insert($con,"group_users",array("group_ID"=>$lastid , "user_ID"=> $user_id));
			}

			return "group created successfully!!";
		}
		else
			return"Something went wrong";
	}

	public static function display_groups($con, $user_id)
	{
		global $current_User;
		if(1==1)
		{
			$groups_IDs=DB::select($con, "group_users", array("group_ID"), "user_ID='".$user_id."'");
			if($groups_IDs != false)
			{

				for($i=0;$i<sizeof($groups_IDs);$i++)
				{
					$groupname=DB::select($con, "groups", array("name"), "groupID='".$groups_IDs[$i]->group_ID
					."'");

					echo '<a href="displaygroup.php?id='.$groups_IDs[$i]->group_ID.'"><li class="list-group-item" user-id ='. $groups_IDs[$i]->group_ID.'> <span>'. $groupname[0]->name.'</span></li></a>';
				}
				return true;
			}
			else
				return false;
		}
		else
			echo "log in to display you friends";
	}


	public static function get_users($con, $group_id)
	{
		$users_IDs=DB::select($con, "group_users", array("user_ID"), "group_ID='".$group_id."'");

		if($users_IDs != false)
		{
			for($i=0; $i<sizeof($users_IDs); $i++)
			{
				$array[$i]=$users_IDs[$i]->user_ID;
			}
			return $array;
		}
		else
		 return false;
	}


		public static function display_users($con, $user_id,$group_id)
	{
		global $current_User;
		if(1==1)
		{
			$group_users=group::get_users($con, $group_id);
			$friends_ID=DB::select($con, "friends", array("friend_ID"), "user_ID='".$user_id."'");
			$friends_IDs =array();

			for($i=0;$i<sizeof($friends_ID);$i++)
			{
				$flag =0;
				for($j=0;$j<sizeof($group_users);$j++)
				{

					if($friends_ID[$i]->friend_ID == $group_users[$j])
						{
							$flag =1;
							break;
						} 
				}
				if($flag ==0)
				{
					$friends_IDs[]= $friends_ID[$i];
				}

			}


			if($friends_IDs != false)
			{
				for($i=0;$i<sizeof($friends_IDs);$i++)
				{
					$username=DB::select($con, "users", array("username"), "ID='".$friends_IDs[$i]->friend_ID
					."'");
					echo '<form validate method ="post"><li class="list-group-item" user-id ='. $friends_IDs[$i]->friend_ID.'><a href="profile.php?id='.$friends_IDs[$i]->friend_ID .'"> <span>'. $username[0]->username.'</span> </a><button style="float:right;"name="adduser" value ="'.$friends_IDs[$i]->friend_ID .'">ADD</button></li></form> ';
				}
				return true;
			}
			else
				return false;
		}
		else
			return "log in to display you friends";
	}

	public static function delete_user($con,$currentUSerID,$delete_userId,$groupId)
	{
		$admin_ID = DB::select($con,"groups",array("adminID"),"groupID='".$groupId."'");
		$admin_ID = $admin_ID[0]->adminID;
		global $current_User;
		if($currentUSerID==$current_User)
		{
			if($delete_userId != $admin_ID)
			{
				if(DB::delete($con, "group_users", array("user_ID"=>$delete_userId, "group_ID" =>$groupId))==true)
					{
						return "user deleted successfuly!";
						
					}
				else 
					return "something went wrong";
			}
			else
			{
				DB::delete($con, "group_users", array("group_ID" =>$groupId));
				DB::delete($con, "groups", array("groupID" =>$groupId));
				DB::delete($con, "posts", array("group_ID" =>$groupId));

			}
		}
		else
			return "log in to remove user";
	}


	public static function add_user($con,$currentUSerID,$add_userId,$groupId)
	{
		$data = array("user_ID"=>$add_userId,"group_ID" =>$groupId);
		if(DB::insert($con,"group_users",$data)==true)
		{
			return "user added successfully!!";
		}
		else
			return"Something went wrong";
	}
	
	public static function display_posts($con,$group_id)
	{
		$posts=DB::select($con, "posts", array("user_ID","post_ID" ,"body","posted_at", "likes", "comments"), "group_ID='".$group_id."' ORDER BY posted_at DESC");
			foreach ($posts as $post ) 
			{
				$query="SELECT username FROM users WHERE ID = ".$post->user_ID."";
				$result = mysqli_query($con, $query);
				$user_names = $result->fetch_object();
				$res=mysqli_query($con,"SELECT ID FROM post_likes WHERE post_ID='$post->post_ID' AND user_ID=".$post->user_ID."");
				if($res->num_rows == 0)
					{$color = "#c5c5c5"; $othercolor = "#eb3b60";}
				else
					{$color = "#eb3b60";  $othercolor = "#c5c5c5";}

				echo"<blockquote><div> <a href=\"profile.php?id=".$post->user_ID."\"> <h4 style=\" color:#337ab7; width:90%; text-transform: capitalize; font-size: 88%;\"> ~ ".$user_names->username."</h4> </a> <button type=\"button\" class=\"close deletepost\" data-dismiss=\"modal\" aria-label=\"Close\" delete-postid=".$post->post_ID ."><span aria-hidden=\"true\" style=\" font-size : 60%; color:red;\">Remove</span></button></button> </div> <p>".($post->body)."</p><footer>Posted on ".($post->posted_at)." 
	                          <form validate method=\"post\" style=\" display: inline-block;\" >
	                          <button onmouseover=\"this.style.color='".$othercolor."'\" onmouseout=\"this.style.color='".$color."'\" class=\"btn btn-default\" type=\"submit\" name=\"likepostid\" value=".$post->post_ID ." style=\"color:".$color."; background-image:url(&quot;none&quot;);background-color:transparent; \"> <i class=\"glyphicon glyphicon-heart\" data-aos=\"flip-right\" ></i><span></span></button></form>

	                          <form validate method=\"post\" style=\" display: inline-block;\"> <button  class=\"btn btn-default\" type=\"submit\" name=\"likers\" value=".$post->post_ID ." style=\"color:#eb3b60;background-image:url(&quot;none&quot;);background-color:transparent;\" like-postid=".$post->post_ID ."> <i class=\"glyphicon \" data-aos=\"flip-right\"></i><span> ".($post->likes)." Likes </span></button></form>


	                             <form class=\"Comments\" method=\"post\" style=\" display: inline-block;\"> <button class=\"btn btn-default comment\" name=\"comments\" type=\"submit\" value=".$post->post_ID." style=\"color:#eb3b60;background-image:url(&quot;none&quot;);background-color:transparent;\"><i class=\"glyphicon glyphicon-flash\" style=\"color:#f9d616;\"></i><span style=\"color:#f9d616;\">".($post->comments)." Comments</span></button></footer>
	                             </form>

								<form method=\"post\" >
								<div style=\"width: 80%;margin-top: 5px; float:left;\"><p  class=\"lead emoji-picker-container\">
				                <textarea data-emojiable=\"true\" data-emoji-input=\"unicode\" rows=\"2\"  style=\"width:80%;  resize:vertical; box-sizing: border-box; border: 2px solid #ccc; border-radius: 4px;\" name=\"comment_body\" class=\"form-control textarea-control\"></textarea></p></div>
				                <button style=\"margin-left: 20px; margin-top: 25px; background-image:url(&quot;none&quot;); background-color:#da052b;color:#fff; border:none;box-shadow:none;text-shadow:none;opacity:0.9;\" type=\"submit\" name=\"comment\" value=".$post->post_ID." class=\"btn btn-default comment\">Comment</button>
				                </form>     

	                             </blockquote>


	                             ";
             
			}
		}
	

	public static function delete_group($con,$currentUSerID,$groupId)
	{

			DB::delete($con, "group_users", array("group_ID" =>$groupId));
			DB::delete($con, "groups", array("groupID" =>$groupId));
			DB::delete($con, "posts", array("group_ID" =>$groupId));
			
			return "Group is deleted succesfully";
	}

}

?>
