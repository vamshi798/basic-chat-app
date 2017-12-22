<?php
	require 'connect.php';
	require 'core.php';

	$first_name_check = isset($_POST['first_name']) && !empty($_POST['first_name']);
	$last_name_check = isset($_POST['last_name']) && !empty($_POST['last_name']);
	$user_name_check = isset($_POST['user_name']) && !empty($_POST['user_name']);
	$pass_field_check = (!empty($_POST['password']) && !empty($_POST['re_password']) && ($_POST['password'] == $_POST['re_password']));
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$user_name = $_POST['user_name'];
	$password = $_POST['password'];
	//fetching values from the post request and validating its existence
	if($first_name_check){
		if($last_name_check){
			if($user_name_check){
				$user_name = $_POST['user_name'];
				if($pass_field_check){
					echo "All details are good";
					$online = 0;
					// Register the user in the database with the details the user submitted
					$query_to_create_new_user = "INSERT INTO `user`(`first_name`, `last_name`, `user_name`, `password`, `online`) values ('$first_name', '$last_name', '$user_name', '$password', '$online')";
					if (mysqli_query($conn, $query_to_create_new_user)){
						echo "User successfully created";
						$query= "Select * from `User` where `user_name`='$user_name'";
						$result=mysqli_query($conn,$query);

						if(mysqli_num_rows($result)!=0){
							while($query_row=mysqli_fetch_array($result)){
								// Setting up session variables and online status
								$id=$query_row['id'];
								$user_name=$query_row['user_name'];
								$_SESSION['user_name']=$user_name;
								$_SESSION['id']=$id;

								$check_online_status= "select `online` from `user` where `user_name`='$user_name'";
								$result_check_online_status = mysqli_query($conn,$check_online_status);

								if(mysqli_num_rows($result_check_online_status)>0){
						      while($query_for_checking_online_status=mysqli_fetch_array($result_check_online_status)){

						      	$status = $query_for_checking_online_status['online'];
						      	if ($status == 0) {
						      		$query_online_status= "Update `user` set `online` = '1' where `user_name`='$user_name'";
											$result_for_online_status=mysqli_query($conn,$query_online_status);
											
											if($result_for_online_status){
												header('Location: app.php');
											}
						      	}
						      	else{
						      		echo 'Sorry '.$user_name.' is already logged in';
						      	}
						      }
						    }
							}
						}

					} else{
						echo "Something went wrong! Couldn't register you";
					}
				} else{
					echo "Please check your password again!";
				}
			} else{
				echo "Please enter a username";
			}
		} else{
			echo "Please enter Last Name";
		}
	} else{
		echo "Please enter First Name";
	}
?>