<?php
  require_once("./database.php"); 

// Loop thru database and return availble users with their status online/offline
    $user=$_GET['q'];
    $sql = "SELECT nickname,status FROM users";
    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_array($result)) {
          echo "<li class='clearfix'>";
          echo  "<img src='https://bootdey.com/img/Content/avatar/avatar1.png' alt='avatar'>";
          echo    "<div class='about' >";
                        echo "<div class='name' >".$row['nickname'] ."</div>";
                        if($row['status']=='online'){
                         echo "<div class='status'> <i class='fa fa-circle online'></i>".$row['status']."</div>";
                        }else{
                          echo "<div class='status'> <i class='fa fa-circle offline'></i>".$row['status']."</div>";
                        }
                                
                        echo "</div>";
          echo "</li>";


}



?>