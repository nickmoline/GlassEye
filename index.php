<?php
echo "<html><head><title>Glass Eye</title></head>Z<body bgcolor=\"black\" text=\"white\"><p align=\"center\"><img src=\"images\header.png\"><br>";

require_once("config.php");
require_once("mirror-client.php");

if ($_POST['operation'] == "installApp")
{
login_user();
}

if (array_key_exists('token', $_SESSION))
{
login_user();
}
else
{
echo "
<form method=\"post\">
  <input type=\"hidden\" name=\"operation\" value=\"installApp\"/>
  <input type=\"image\" src=\"images\yes.png\" name=\"submit\" border=\"0\">
</form>";
}

echo "</p></body></html>";
?>