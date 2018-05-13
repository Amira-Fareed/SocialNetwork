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
					echo '<a href="profile.php?id='.$friends_IDs[$i]->friend_ID .'"><li class="list-group-item" user-id ='. $friends_IDs[$i]->friend_ID.'> <span>'. $username[0]->username.'</span></li></a>';
				}
				return true;
			}
			else
				return false;
		}
		else
			echo "log in to display you friends";
	}
	

}

?>