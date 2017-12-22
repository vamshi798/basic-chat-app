<?php

	require 'connect.php';
	require 'core.php';

	$user_id = $_SESSION['id'];
	$logout_query= "Update `user` set `online` = '0' where `id`='$user_id'";
	$result=mysqli_query($conn,$logout_query);
	session_destroy(); // destroy the session created in core.php
	header('Location: login.php');

?>