<?php

    require 'connect.php';
    
    $friend_id_1 = $_POST['user1'];
    $friend_id_2 = $_POST['user2'];
    $chat_message_content = $_POST['message'];
    
    $date = new DateTime("now", new DateTimeZone('America/Los_Angeles') ); // Set time zone for my database
    $timestamp = (string)$date->format('Y-m-d H:i:s'); // Format time to show current time

    $query="INSERT INTO `chat_messages`(`user_1`,`chat_user_2`,`chat_message_content`, `timestamp`) values ('$friend_id_1', '$friend_id_2', '$chat_message_content', '$timestamp')"; //query to insert new message into the table.

    if (mysqli_query($conn, $query)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
?>
