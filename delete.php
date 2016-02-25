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

$fav = $mysqli->real_escape_string($_POST['fav']);
$sql = "DELETE FROM recipes WHERE recipes.id = '".$recipe_id."'";
$results = $mysqli->query($sql);
?>
