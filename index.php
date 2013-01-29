<?php
session_start();
//ini_set('display_errors','On');
require_once("config.php");
require_once("mirror-client.php");

$token = null;
if ($_POST['operation'] == "installApp") {
  $token = login_user();
}

if (array_key_exists('token', $_SESSION)) {
  $token = login_user();
}

echo "<html><head><title>Glass Eye</title><link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type=text/css'></head><body bgcolor=\"black\" text=\"white\"><p align=\"center\"><img src=\"images/header.png\"></p>";

if ($token) {
echo "<p align=\"center\"><span style=\"font-family: 'Roboto', sans-serif;\"><b>You have installed this application.</b></p>
<p align=\"center\"><span style=\"font-family: 'Roboto', sans-serif;\">Turn on the Share Target for Glass Eye below.</p>
<p align=\"center\"><a href=\"https://glass.sandbox.google.com/glass/fe/services\"><img src=\"images/buttonTargets.png\" border=\"0\"></a></p>";
} else {
echo "
<form method=\"post\">
  <input type=\"hidden\" name=\"operation\" value=\"installApp\"/>
  <p align=\"center\"><input type=\"image\" src=\"images/button.png\" name=\"submit\" border=\"0\"></p>
</form>";
}

echo "</body></html>";
?>