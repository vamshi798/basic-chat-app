<html>
	<head>
		<link type="text/css" rel="stylesheet" href="style.css" />
	</head>
	<body>
		<?php 
		require 'connect.php';
		require 'core.php';

		// validate presence and existence of username and password
		if(isset($_POST['user_name']) && isset($_POST['password'])){
			$user_name=$_POST['user_name'];
			$password=$_POST['password'];
			if(!empty($user_name) || !empty($password)){

				$query= "Select * from `user` where `user_name`='$user_name' and `password`='$password'";
				$result=mysqli_query($conn,$query);

				if(mysqli_num_rows($result)!=0){
					while($query_row=mysqli_fetch_array($result)){
						//setting up session variables
						$id=$query_row['id'];
						$user_name=$query_row['user_name'];
						$_SESSION['user_name']=$user_name;
						$_SESSION['id']=$id;

						$check_online_status= "select `online` from `user` where `user_name`='$user_name'";
						$result_check_online_status = mysqli_query($conn,$check_online_status);

						if(mysqli_num_rows($result_check_online_status)>0){
				      while($query_for_checking_online_status=mysqli_fetch_array($result_check_online_status)){

				      	$status = $query_for_checking_online_status['online'];
				      	if ($status == 0) { // check status before login
				      		$query_online_status= "Update `user` set `online` = '1' where `user_name`='$user_name'";
									$result_for_online_status=mysqli_query($conn,$query_online_status);
									
									if($result_for_online_status){
										header('Location: app.php'); // redirect once all credentials have been verified with the database
									}
				      	}
				      	else{
				      		echo 'Sorry '.$user_name.' is already logged in';
				      	}

				      }
				    }
					}
				}
				else {
					echo "Username or password is invalid";
				}
			}
		}
		else{
			echo "Please enter your credentials";
		}


		?>
		<!-- form to login to the system -->
		<div id="sign_in">
			<form action="<?php echo $currentFile; ?>"  method="POST" id="loginform">
				<table style="margin-left:25%">
					<tbody>
						<tr>
							<td style="width:50%;float:right">
								Name:
							</td>
							<td style="width:50%;float:left">
								<input type="text" name="user_name" placeholder="Enter you name here"><br>
							</td>
						</tr>
						<tr>
							<td style="width:50%;float:right">
								Password:
							</td>
							<td style="width:50%;float:left">
								<input type="password" name="password" placeholder="Enter your password"><br>
							</td>
						</tr>
					</tbody>
				</table>
				<input type="submit" value="Log On">
			</form>
		</div>
		<!-- form to register new user -->
		<div id="sign_up">
			<p style='margin-top:10px'> New User? Sign up </p>
			<form action="sign_up.php"  method="POST" id="registration_form">
				<table style="margin-left:25%">
					<tbody>
						<tr>
							<td style="width:50%;float:right">
								First Name:
							</td>
							<td style="width:50%;float:left;">
								<input type="text" name="first_name" placeholder="Enter your first name here" style="width:150px"><br>
							</td>
						</tr>
						<tr>
							<td style="width:50%;float:right">
								Last Name:
							</td>
							<td style="width:50%;float:left;">
								<input type="text" name="last_name" placeholder="Enter your last name" style="width:150px"><br>
							</td>
						</tr>
						<tr>
							<td style="width:50%;float:right">
								User name:
							</td>
							<td style="width:50%;float:left;">
								<input type="text" name="user_name" placeholder="Enter your User Name here" style="width:150px"><br>
							</td>
						</tr>
						<tr>
							<td style="width:50%;float:right">
								Password:
							</td>
							<td style="width:50%;float:left;">
								<input type="password" name="password" placeholder="Enter your password" style="width:150px"><br>
							</td>
						</tr>
						<tr>
							<td style="width:50%;float:right">
								Re-enter Password:
							</td>
							<td style="width:50%;float:left">
								<input type="password" name="re_password" placeholder="Re-enter your password" style="width:150px"><br>
							</td>
						</tr>
					</tbody>
				</table>
				<input type="submit" value="Register">
			</form>
		</div>
	</body>
</html>