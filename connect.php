<?php
	
	// setup the connection variables
	$host = 'localhost';
	$as = 'root';
	$pass = '';
	$env='Development';

	$conn = mysqli_connect($host, $as,$pass, $env);

	if(!$conn){
		echo "Error Connecting to Database";
	}

?>