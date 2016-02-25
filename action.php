<?php
  error_reporting(E_ALL);
  ob_start();
  session_start();

  ini_set('display_errors','On');

  $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

  if (!$mysqli || $mysqli->connect_errno) {
    echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
  }

#main.php functions:
  if (isset($_POST["add"])) {
    addToDatabase();
  }
  if (isset($_POST["favorite"])) {
    favoriteRecipe();
  }
  if (isset($_POST["unfav"])) {
    unfavoriteRecipe();
  }
  if (isset($_POST["remove"])) {
    removeRecipe();
  }

#mealtypes.php functions:
  if (isset($_POST["addMealType"])) {
    addMealType();
  }
  if (isset($_POST["editMealType"])) {
    editMealType();
  }
  if (isset($_POST["removeMealType"])) {
    removeMealType();
  }

  #pantry.php functions:
  if (isset($_POST["addToPantry"])) {
    addToPantry();
  }
  if (isset($_POST["editPantry"])) {
    editPantry();
  }
  if (isset($_POST["removeFromPantry"])) {
    removeFromPantry();
  }

  #profile.php functions:
  if (isset($_POST["editUserEmail"])) {
    editUserEmail();
  }
  if (isset($_POST["editUserPass"])) {
    editUserPass();
  }

  function addToDatabase() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $name = $_POST["name"];
    $meal_type = $_POST["meal_type"];
    $cooking_time = $_POST["cooking_time"];
    $main_ingredient_1 = $_POST["main_ingredient_1"];
    $main_ingredient_2 = $_POST["main_ingredient_2"];
    $username = $mysqli->real_escape_string($_SESSION['user']);

    if (isset($_FILES['uploaded_file'])) {
      if ($_FILES['uploaded_file']['error'] == 0) {
        $recipe = $mysqli->real_escape_string(file_get_contents($_FILES ['uploaded_file']['tmp_name']));
      }
    }

    if ($name == NULL) {
      echo "The name field is a required field and must be unique.";
      echo "<meta http-equiv=\"refresh\" content=\"2;URL=addRecipe.php\">";
      exit(1);
    }

    if(!($adding = $mysqli->prepare("INSERT INTO recipes (name, meal_type, cooking_time, main_ingredient_1, main_ingredient_2, recipe, username) VALUES (?,?,?,?,?,?,?)"))) {
      echo "Prepare failed.";
    }

    if (!$adding->bind_param("ssissss", $name, $meal_type, $cooking_time, $main_ingredient_1, $main_ingredient_2, $recipe, $username)) {
      echo "Binding parameters failed.";
    }

    if (!$adding->execute()) {
?>
      <script>
        alert("Failed to meet requirements. Try again.");
      </script>

<?php

      echo "<meta http-equiv=\"refresh\" content=\"0;URL=addRecipe.php\">";
      exit(1);
    }

    $statement = "SELECT MAX(id) FROM recipes";
    $results = $mysqli->query($statement);
    $row = $results->fetch_row();
      echo "<meta http-equiv=\"refresh\" content=\"0;URL=addPhoto.php?id=".$row[0]."\">";
  }

  function favoriteRecipe() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $id = $_POST["id"];
    $fav = $mysqli->prepare("UPDATE recipes SET favorite = 1 WHERE id = ?");
    $fav->bind_param("i", $id);
    $fav->execute();
    $fav->close();
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=main.php\">";
  }

  function unfavoriteRecipe() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $id = $_POST["id"];
    $unfav = $mysqli->prepare("UPDATE recipes SET favorite = 0 WHERE id = ?");
    $unfav->bind_param("i", $id);
    $unfav->execute();
    $unfav->close();
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=main.php\">";
  }

  function removeRecipe() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $id = $_POST["id"];
    $remove = $mysqli->prepare("DELETE FROM recipes WHERE id = ?");
    $remove->bind_param("i", $id);
    $remove->execute();
    $remove->close();
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=main.php\">";
  }

  function addMealType() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $type = $_POST["meal_type_add"];
    $username = $mysqli->real_escape_string($_SESSION['user']);

    if(!($adding = $mysqli->prepare("INSERT INTO meal_type (type, username) VALUES (?,?)"))) {
      echo "Prepare failed.";
    }

    if (!$adding->bind_param("ss", $type, $username)) {
      echo "Binding parameters failed.";
    }

    if (!$adding->execute()) {
?>
      <script>
        alert("Failed to meet requirements. Try again.");
      </script>

<?php
      echo "<meta http-equiv=\"refresh\" content=\"0;URL=mealtypes.php\">";
      exit(1);
    }

    echo "<script>
    window.onunload = function() {
       window.opener.location.reload();
    };</script>";

    echo "<meta http-equiv=\"refresh\" content=\"0;URL=mealtypes.php\">";
  }

  function editMealType(){
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $mid = $_POST["id"];
    $name = $_POST["edit"];

    $fav = $mysqli->prepare("UPDATE meal_type SET type = ? WHERE mid = ?");
    $fav->bind_param("si", $name, $mid);
    $fav->execute();
    $fav->close();

    echo "<script>
    window.onunload = function() {
       window.opener.location.reload();
    };</script>";

    echo "<meta http-equiv=\"refresh\" content=\"0;URL=mealtypes.php\">";
  }

  function removeMealType() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $mid = $_POST["id"];
    $remove = $mysqli->prepare("DELETE FROM meal_type WHERE mid = ?");
    $remove->bind_param("i", $mid);
    $remove->execute();
    $remove->close();

    echo "<script>
    window.onunload = function() {
       window.opener.location.reload();
    };</script>";

    echo "<meta http-equiv=\"refresh\" content=\"0;URL=mealtypes.php\">";
  }

  function addToPantry() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $ingredient = $_POST["pantry_ingredient_add"];
    $username = $mysqli->real_escape_string($_SESSION['user']);

    if(!($adding = $mysqli->prepare("INSERT INTO pantry (p_ingredient, username) VALUES (?,?)"))) {
      echo "Prepare failed.";
    }

    if (!$adding->bind_param("ss", $ingredient, $username)) {
      echo "Binding parameters failed.";
    }

    if (!$adding->execute()) {
?>
      <script>
        alert("Failed to meet requirements. Try again.");
      </script>

<?php
      echo "<meta http-equiv=\"refresh\" content=\"0;URL=pantry.php\">";
      exit(1);
    }

    echo "<script>
    window.onunload = function() {
       window.opener.location.reload();
    };</script>";

    echo "<meta http-equiv=\"refresh\" content=\"0;URL=pantry.php\">";
  }

  function editPantry() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $pid = $_POST["id"];
    $ingredient = $_POST["editIngPantry"];

    $fav = $mysqli->prepare("UPDATE pantry SET p_ingredient = ? WHERE pid = ?");
    $fav->bind_param("si", $ingredient, $pid);
    $fav->execute();
    $fav->close();

    echo "<script>
    window.onunload = function() {
       window.opener.location.reload();
    };</script>";

    echo "<meta http-equiv=\"refresh\" content=\"0;URL=pantry.php\">";
  }

  function removeFromPantry() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $pid = $_POST["id"];
    $remove = $mysqli->prepare("DELETE FROM pantry WHERE pid = ?");
    $remove->bind_param("i", $pid);
    $remove->execute();
    $remove->close();

    echo "<script>
    window.onunload = function() {
       window.opener.location.reload();
    };</script>";

    echo "<meta http-equiv=\"refresh\" content=\"0;URL=pantry.php\">";
}

function editUserEmail() {
  $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

  if (!$mysqli || $mysqli->connect_errno) {
    echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
  }

  $userN = $_POST["userN"];
  $email = $_POST["editEmail"];

  $fav = $mysqli->prepare("UPDATE users SET email = ? WHERE username = ?");
  $fav->bind_param("ss", $email, $userN);
  $fav->execute();
  $fav->close();

  echo "<meta http-equiv=\"refresh\" content=\"0;URL=profile.php\">";
}

function editUserPass() {
  $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

  if (!$mysqli || $mysqli->connect_errno) {
    echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
  }

  $userN = $_POST["userN"];
  $pass = $_POST["editPass"];

  $fav = $mysqli->prepare("UPDATE users SET password = ? WHERE username = ?");
  $fav->bind_param("ss", $pass, $userN);
  $fav->execute();
  $fav->close();

  echo "<meta http-equiv=\"refresh\" content=\"0;URL=profile.php\">";
}

?>
