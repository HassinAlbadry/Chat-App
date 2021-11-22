<?php
session_start();
// handle messages sent by ajax and save to log.html along with session nickname to retrieve later on chat window
$myMessage =$_GET['myMessage'];
$filename = './log.html';
$content =' <li class="clearfix">
            
            <div class="message-data">
            <span class="message-data-time">'.'sent by '.$_SESSION['nickname'].',  '.date(l).', '.date("h:ia").'</span>
            </div>
            
            <div class="message my-message" id="ha-my-message">'.$myMessage. '</div>
            
            </li>';

file_put_contents($filename, $content, FILE_APPEND);




?>