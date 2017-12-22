<?php

    $conn = mysqli_connect("localhost","root","", "Development");
    if(!$conn){
      echo "Error Connecting to Database";
    }
    // get details from the post request
    $id1 = $_GET['id1'];
    $id2 = $_GET['id2'];

    $user_name1;
    $user_name2;
    $username1=array();
    $timestamp=array();
    $message=array();
    $i=0;

    $query = "Select `user_name` from `User` where `id` = '$id1'";
    $result=mysqli_query($conn,$query);

    if(mysqli_num_rows($result)!=0){
      while($query_row=mysqli_fetch_array($result)){
        global $user_name1;

        $user_name1=$query_row['user_name'];
      }
    }

    $query = "Select `user_name` from `User` where `id` = '$id2'";
    $result=mysqli_query($conn,$query);

    if(mysqli_num_rows($result)!=0){
      while($query_row=mysqli_fetch_array($result)){
        global $user_name2;

        $user_name2=$query_row['user_name'];
      }
    }

    // Query to get message between current user and friend
    $query="
      Select `user_1`,`chat_message_content`, `timestamp` from `chat_messages` where `user_1`='$id1' AND `chat_user_2` = '$id2'
      UNION
      Select `user_1`,`chat_message_content`, `timestamp` from `chat_messages` where `user_1`='$id2' AND `chat_user_2` = '$id1'
      ORDER BY `timestamp`
    ";

    $result1=mysqli_query($conn,$query);
    if($result1) {
      if(mysqli_num_rows($result1)>0){
        $count = mysqli_num_rows($result1);
        while($query_row1=mysqli_fetch_array($result1)){

          global $username1,$timestamp,$message;
          global $i;
          global $id1,$id2;
          global $user_name1,$user_name2;

          if($query_row1['user_1'] == $id1) {
            $username1[$i]=$user_name1;
          } else if($query_row1['user_1'] == $id2){
            $username1[$i]=$user_name2;
          }
          
          $timestamp[$i]= $query_row1['timestamp'];
          
          $message[$i]=$query_row1['chat_message_content'];
          //echo $message.'<br>';
          $i++;
        }
    }
    

      for($x=0;$x<$count;$x++){
        echo nl2br($username1[$x].' '.$timestamp[$x].': '.$message[$x]."\n");
      }
    }   
?>
