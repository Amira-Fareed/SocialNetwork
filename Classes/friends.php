<?php
include 'DB.php';
session_start();
$current_User=$_SESSION['loggedin'];

class friends
{
	public static function add_friend($con, $user_id, $friend_id)
	{
		global $current_User;
		if($user_id==$current_User)
		{
			$friends=DB::select($con, "friends", array("user_ID","friend_ID"), "user_ID='".$user_id."' And friend_ID='".$friend_id."'");
			if($user_id===$friend_id || $friends!=false)
			{
				if($friends!=false)
					echo "you are already friends!";
				return false;
			}
			else
			{
				$data=array('user_ID'=>$user_id, 'friend_ID'=>$friend_id);
				$data1=array('user_ID'=>$friend_id, 'friend_ID'=>$user_id);
				if(DB::insert($con, "friends", $data)===true && DB::insert($con, "friends", $data1)===true)
					{
						echo '<script  type="text/javascript"> dothis();</script>';
						return true;
					}
				else
					return false;
			}
		}
		else
			echo "log in to add friends";
	}
	public static function remove_friend($con, $user_id, $friend_id)
	{
		global $current_User;
		if($user_id==$current_User)
		{
			$friends=DB::select($con, "friends", array("user_ID","friend_ID"), "user_ID='".$user_id."' And friend_ID='".$friend_id."'");

			if($user_id===$friend_id || $friends===false)
			{
				if( $friends===false)
					echo "you are already friends :) ";
				return false;
			}
			else
			{
				$data=array('user_ID'=>$user_id, 'friend_ID'=>$friend_id);
				$data1=array('user_ID'=>$friend_id, 'friend_ID'=>$user_id);
				if(DB::delete($con, "friends", $data)===true && DB::delete($con, "friends", $data1)===true)
					return true;
				else
					return false;
			}
		}
		else
			echo "log in to remove a friend";
	}
	public static function display_friends($con, $user_id)
	{
		global $current_User;
		if(1==1)
		{
			$friends_IDs=DB::select($con, "friends", array("friend_ID"), "user_ID='".$user_id."'");

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
	
	public static function get_friends($con, $user_id)
	{
		$friends_IDs=DB::select($con, "friends", array("friend_ID"), "user_ID='".$user_id."'");

		if($friends_IDs != false)
		{
			for($i=0; $i<sizeof($friends_IDs); $i++)
			{
				$array[$i]=$friends_IDs[$i]->friend_ID;
			}
			return $array;
		}
		else
		 return false;
	}

public static function search_friends($con ,$info )
{
	$friends_list = DB::select($con, "users", array("ID"), " username LIKE'%".$info."%' OR email ='".$info."'");
	if($friends_list != false)
		{

			for($i=0; $i<sizeof($friends_list); $i++)
			{
				$array[$i]=$friends_list[$i]->ID;
			}
			return $array;
		}
	else
		return false;
}

}


?>