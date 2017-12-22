<?php
	require 'connect.php';
	require 'core.php';

	$another_user_id=0;

	if(isset($_POST['user_name']) && isset($_SESSION['id'])){ //check for username and id of current user before adding friends
		$current_user_id = $_SESSION['id']; // get users current id
		$user_name = $_POST['user_name']; // get users username
		if(!empty($user_name)){
			$query = "select `id` from `User` where `user_name` = '$user_name'";
			$result = mysqli_query($conn, $query);

			if(mysqli_num_rows($result)>0){
				while($query_row=mysqli_fetch_array($result)){
					global $another_user_id;
					$another_user_id=$query_row['id'];
				}
			}
		}

		// Create friendship between current user and new friend and vice-versa
		$new_query_1 = "INSERT INTO `friends`(`friend_id_1`,`friend_id_2`) values ('$current_user_id', '$another_user_id')";
		$new_query_2 = "INSERT INTO `friends`(`friend_id_1`,`friend_id_2`) values ('$another_user_id', '$current_user_id')";
		
		if (mysqli_query($conn, $new_query_1) && mysqli_query($conn, $new_query_2)){
			header('Location: app.php');
		}	
	}
		
?>