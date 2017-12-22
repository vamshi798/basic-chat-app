<html>
<head>
	<link type="text/css" rel="stylesheet" href="style.css" />
</head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<body>

	<?php 
		require 'connect.php'; // connecting to the database
		include 'core.php'; // mainly to start session

			
		//$count=sizeof(friend_usernames);
		function loggedin(){
			if(isset($_SESSION['user_name'])&& !empty($_SESSION['user_name'])) //check if session has these details
				return true;
			else
				return false;
		}

		if(loggedin()){
			$user_name=$_SESSION['user_name']; // set username of current user in session
			echo '<br> <a style="float:right;top:10px; right:20px; position: absolute" href="logout.php">Logout</a>';
			echo "You are logged in as ".$_SESSION['user_name'];
			$id=$_SESSION['id']; // set id of current user

			$i=0;
			$j=0;
			$friend_ids=array();
			$friend_usernames=array();
			$query="Select * from friends where `friend_id_1`='$id'"; // fetch id of all the friends of current user

			$result=mysqli_query($conn,$query);

			if(mysqli_num_rows($result)>0){
				$count=mysqli_num_rows($result);
				while($query_row=mysqli_fetch_array($result)){
					global $i;
					global $friend_ids;
					$friend_ids[$i]=$query_row['friend_id_2']; // fetch friend ID
					$i++;
				}
			}

			echo '<br>';
			
			for($x=0;$x<sizeof($friend_ids);$x++)
			{
				$query1="Select user_name from user where `id`='$friend_ids[$x]'"; // fetch username of all friends of current user

				$result=mysqli_query($conn,$query1);
				
				if(mysqli_num_rows($result)>0){
					while($query_row=mysqli_fetch_array($result)){
						global $j;
						global $friend_usernames;	
						$friend_usernames[$j]=$query_row['user_name'];
						$j++;
					}
				}
			}
		}
		else {
			header ('Location: login.php'); 
		}
	?>

<script type="text/javascript">

	// setting up values from php to javascript
	var ab='<?php echo $count ; ?>';
	var current_user_id='<?php echo $id ; ?>';
	var user_id=0;

  var friends_username_array = new Array();
  <?php foreach($friend_usernames as $key => $val){ ?>
      friends_username_array.push('<?php echo $val; ?>');
  <?php } ?>

  var friends_id_array = new Array();
  <?php foreach($friend_ids as $key => $val){ ?>
      friends_id_array.push('<?php echo $val; ?>');
  <?php } ?>

  var div_head = document.createElement('div');
  div_head.setAttribute('id', 'chathead');
  div_head.setAttribute('style', 'float:left;font-weight:bold;font-size:25px;')
  div_head.innerHTML = "Your Friends" + '<br>';
  document.body.appendChild(div_head);

  // Add Friend to current user
  var div_add_friend = document.createElement('div');
  div_add_friend.setAttribute('style', 'float:right;');
  div_add_friend.innerHTML = 'Add Friends';
  div_add_friend.setAttribute('onclick', 'show_form()');
  document.body.appendChild(div_add_friend);

  function show_form(){
  	document.getElementById('new_friend_request').setAttribute('style', 'display:block;float:right;');
  }

	for (var x = 0; x < ab; x++) {
		var div=document.createElement("div");
		var created_id = "input_value"+x+'';
		div.setAttribute('id', created_id);
		div.setAttribute('style', 'float: left;clear:both;font-weight:normal;font-size:20px;')
		div.innerHTML = friends_username_array[x];
		div.setAttribute('data-user-id', friends_id_array[x]);
		div.setAttribute('data-user-name', friends_username_array[x]);
		div_head.appendChild(div);
	}

	for (var x = 0; x < ab; x++) {
		document.getElementById('input_value'+x+'').addEventListener("click", function(x){
			user_id = x.toElement.getAttribute('data-user-id');
			var friend_name = x.toElement.getAttribute('data-user-name');
    	document.getElementById("demo").innerHTML = '';
    	
    	// Create chatbox
    	var div = document.createElement('div');
    	div.setAttribute('id', 'chatbox');
    	div.setAttribute('style', 'height:200px;width:50em;border: 1px solid; overflow:scroll');
    	document.getElementById('demo').appendChild(div);
  		
  		// Create input box where user enters chat message
  		var input_msg_box = document.createElement("input");
  		input_msg_box.setAttribute('name', 'usermsg');
  		input_msg_box.setAttribute('id', 'usermsg');
  		input_msg_box.setAttribute('style', 'width:93%');
  		input_msg_box.setAttribute('type', 'text');
  		document.getElementById('demo').appendChild(input_msg_box);
  		
  		// Create a button to submit message
  		var button = document.createElement("input");
  		button.setAttribute('name', 'submitmsg');
  		button.setAttribute('id', 'submitmsg');
  		button.setAttribute('style', 'margin-top:10px');
  		button.setAttribute('type', 'submit');
  		button.setAttribute('value', 'Send');
  		button.setAttribute('data-user-id', user_id);
  		button.setAttribute('onclick', 'submit_my_msg()');
  		document.getElementById('demo').appendChild(button);
  		
  		// Description of whom user is chatting with
  		var description = document.createElement('p');
  		description.setAttribute('style', 'margin-top:10px;')
  		description.innerHTML = "Chatting with " + friend_name;
  		document.getElementById('demo').appendChild(description);
	});

	function submit_my_msg(){
		// Submit message to the server for creating new message from current user to friend
		var message = document.getElementById('usermsg').value;
		var friend_id_2 = document.getElementById('submitmsg').getAttribute('data-user-id');
		// Object with parameters to create a new message
		var object = {user1: current_user_id, user2: friend_id_2, message: message};
		$.ajax({ 
			type: "post",
	    url: "post_record.php",
	    data: object,
	    success:  function(data){
	    	fetch_my_msg(); // once message is pushed to the server, we fetch it back and display it to the user
	    	document.getElementById('usermsg').value = '';
	    }
    });
	}

	function fetch_my_msg(){
		// Get chat messages from the server.
		var friend_id = user_id;
		$.ajax({ 
			type: "get",
	    url: "fetch_records.php",
	    data: {id1: current_user_id, id2: friend_id},
	    success:  function(data){
	    	console.log(data);
	    	document.getElementById('chatbox').innerHTML = '';
	    	var data_el = document.createElement('div');
	    	data_el.innerHTML = data;
	    	document.getElementById('chatbox').appendChild(data_el);
	    	var elem = document.getElementById('chatbox');
  			elem.scrollTop = elem.scrollHeight;
	    }
    });
    // To fetch the message from the server every one second
		setTimeout(function(){
			fetch_my_msg();
		}, 1000)
	}
};

window.onload = fetch_my_msg; // Function on page load
</script>
<p id="demo">
<div id="new_friend_request" style="display:none">
	<!-- Login form with username and password -->
	<form action="new_friend.php"  method="POST" id="loginform">
		<input type="text" name="user_name" placeholder="Enter your friends Username" style="width:175px;"><br>
		<input type="submit" value="Add" style="float:right; margin-top:5px">
	</div>
</div>
</p>
</body>
</html>