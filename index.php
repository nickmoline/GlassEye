<?php
session_start();
require_once("config.php");
require_once("mirror-client.php");

$token = null;
if ($_POST['operation'] == "installApp") {
  $token = login_user();
}

if (array_key_exists('token', $_SESSION)) {
  $token = login_user();
}

echo "<html><head><title>Glass Eye</title></head><body bgcolor=\"black\" text=\"white\"><p align=\"center\"><img src=\"images\header.png\"></p>";

if ($token) {

} else {
echo "
<form method=\"post\">
  <input type=\"hidden\" name=\"operation\" value=\"installApp\"/>
  <p align=\"center\"><input type=\"image\" src=\"images\button.png\" name=\"submit\" border=\"0\"></p>
</form>";
}

echo "</body></html>";
?>