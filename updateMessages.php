<?php
// Read saved messages in log.html and return to ajax request
$myfile = fopen("./log.html", "r") or die("Unable to open file!");
echo fread($myfile,filesize("./log.html"));
fclose($myfile);

?>