<?php
error_reporting(E_ALL);
ob_start();
session_start();

include 'connect.php';

if(!isset($_SESSION['user'])) {
 header("Location: index.php");
}

$recipe_id = $_GET["id"];
$username = $mysqli->real_escape_string($_SESSION['user']);
$fav = 1;

$sql = "SELECT id FROM favs WHERE recipe_id = '".$recipe_id."' AND username = '".$username."'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
if ($result->num_rows > 0) {
  $sql = "UPDATE favs SET favorite = 1 WHERE recipe_id = '".$recipe_id."' AND username = '".$username."'";
  if (mysqli_query($mysqli, $sql)) {
      echo $row['id'];
  }
} else {
    $sql = "INSERT INTO favs (favorite, recipe_id, username) VALUES ('".$fav."', '".$recipe_id."', '".$username."')";
    if (mysqli_query($mysqli, $sql)) {
        echo "0";
    }
}
?>
