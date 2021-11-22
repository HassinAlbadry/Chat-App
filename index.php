<?php 
session_start();
//include database file to connect to retrieve users
require_once("./database.php"); 

//function to validate and sanitize form input
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


//if user exists change status to online. if not,add user to db with online status. setup session for user. 
if(isset($_POST["name"])){
$checkUser=$_POST["name"];
$sql = "SELECT nickname FROM users WHERE nickname='$checkUser'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
   if($row[nickname]==$checkUser){
    //update status to online if user exist
    $sql1 = "UPDATE users SET status='online' WHERE nickname='$checkUser'";
    $_SESSION["nickname"] = test_input($_POST["name"]);

    if ($conn->query($sql1) === TRUE) {
  //echo "Record updated successfully";
} else {
  //echo "Error updating record: " . $conn->error;
}

}
       

   
  }
} else {
    //insert nickname to db if user doesn't exist.
   $sql2 = "INSERT INTO users (nickname,status) VALUES ('$checkUser','online')";
   $_SESSION["nickname"] = test_input($_POST["name"]);
    if ($conn->query($sql2) === TRUE) {
      //echo "New record created successfully";
    } else {
      echo "Error: " . $sql2 . "<br>" . $conn->error;
    }

}



}

//if user clicked logout then update status record to offline and destroy session
    if(isset($_POST['logout'])){

        $logout_nickname=$_SESSION['nickname'];
        echo $logout_nickname;

        $change_status = "UPDATE `users` SET status = 'offline'  WHERE nickname='$logout_nickname'  ";

        if ($conn->query($change_status) === TRUE) {
           // echo "Record updated successfully";
          } else {
            echo "Error updating record: " . $conn->error;
          }
          
          $conn->close();

          session_unset();
          session_destroy();
       }
  





//deny direct access to page and redirect to signin page. 
if(!isset($_SESSION['nickname'])){
    echo "Youre not signed in!";
    header("Location: http://thermospace/chat_test/signin-to-chat");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>chat app </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

<div class="container">
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card chat-app">
            <div id="plist" class="people-list">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
                <ul class="list-unstyled chat-list mt-2 mb-0" id="sync-db">
                    
                
                    <!-- online users generated & updated from database every 1 second-->
                    
                   
                </ul>
            </div>
            <div class="chat">
                <div class="chat-header clearfix">
                    <div class="row">
                        <div class="col-lg-6">
                                       

                            <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                            </a>
                            <div class="chat-about">
                               
                                <h6 class="m-b-0"><?php echo $_SESSION['nickname']; ?></h6>
                                <small>online</small>
                            </div>
                        </div>
                        <div class="col-lg-6 hidden-sm text-right">
                            <a href="javascript:void(0);" class="btn btn-outline-secondary"><i class="fa fa-camera"></i></a>
                            <a href="javascript:void(0);" class="btn btn-outline-primary"><i class="fa fa-image"></i></a>
                            <a href="javascript:void(0);" class="btn btn-outline-info"><i class="fa fa-cogs"></i></a>
                            <a href="javascript:void(0);"><i class="fa "><form action="./index.php" method="post">
                            <input type="submit"  class="btn btn-outline-warning"  name="logout" value="logout?">
                            </form></i></a>
                            
                            
                        </div>
                    </div>
                </div>
                <div class="chat-history">
                    <ul class="m-b-0" id="ha-read-from-file">
                       <!-- <li class="clearfix">
                            <div class="message-data text-right">
                                <span class="message-data-time">10:10 AM, Today</span>
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar">
                            </div>
                            <div class="message other-message float-right"> Hi Aiden, how are you? How is the project coming along? </div>
                        </li>
                        <li class="clearfix">
                            <div class="message-data">
                                <span class="message-data-time">10:12 AM, Today</span>
                            </div>
                            <div class="message my-message" id="ha-my-message">Are we meeting today?</div>

                        </li>     
                                                  
                        -->
                    </ul>
                </div>
                <div class="chat-message clearfix">
                    <div class="input-group mb-0">
                        <div class="input-group-prepend">
                            <span class="input-group-text" onclick="submitForm(document.getElementById('ha-message').value)"><i class="fa fa-send"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Enter text here..." id="ha-message">                                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<script type="text/javascript">
// this function is set to run every half second to retrieve and update sent messages on screen.
const updateMessages=setInterval(function(){
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var updateMessage = xmlhttp.responseText;
           
            document.getElementById("ha-read-from-file").innerHTML=updateMessage;
            
            }
        };
        xmlhttp.open("GET","updateMessages.php?myMessage=",true);
        xmlhttp.send();
},500);

//this function is triggered when message submitted by user. It sends data to post.php and eventually gets saved to log.html
function submitForm(str) {
   
        xmlhttp.open("GET","post.php?myMessage="+str,true);
        xmlhttp.send();

        //clears user input when click send message.
        document.getElementById('ha-message').value='';
    
}
</script>
<script>
    //this function runs every second to retrieve and refresh the list of availble users with their status.
    const interval = setInterval(function() {
   
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let users = xmlhttp.responseText;
        document.getElementById("sync-db").innerHTML = users;
      }
    };
    xmlhttp.open("GET", "sync.php?q=" +'str' , true);
    xmlhttp.send();
  
 }, 1000);

</script>
</body>
</html>



