<?php
error_reporting(E_ALL);
ob_start();
session_start();

include_once 'connect.php';

if(!isset($_SESSION['user'])) {
 header("Location: index.php");
}

$username = $mysqli->real_escape_string($_SESSION['user']);

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>The Recipe Book</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/animate.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include("navigation.php"); ?>
<main>
<div class="container">
<div class="row">
  <section class="col-md-8">
    <article class="blog-item">
      <div class="row">
        <div class="col-md-12">
        <p> TOTALLY Under Construction </p>
        <form method="POST" action="main.php">

          <label>Search by What's In Your Pantry: <select name="searchByPantryItem">
                    <?php
                      $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

                      if (!$mysqli || $mysqli->connect_errno) {
                        echo "Error connection to MySQLi Session(".$mysqli->connect_errno."): ".$mysqli->connect_error;
                      }

                      $display_pantry_ingredients = "SELECT DISTINCT P.p_ingredient FROM pantry P WHERE P.username = '".$username."' ORDER BY P.p_ingredient ASC";

                      if ($all = $mysqli->query($display_pantry_ingredients)) {
                        while ($row = $all->fetch_row()) {
                          echo '<option name="searchByPantryItem" value="'.$row[0].'">'.$row[0].'</option>';
                        }
                      }
                      $all->close();
                    ?>
              </select> <input type="submit" name="pantrySearch" value="Search"></label>
          </form>
          <form method="POST" action="main.php">
                <label>Search by Max Cooking Time: <input type="number" min="1" max="400" name="cooking_time"> <input type="submit" name="filterTime" value="Search"></label>
              </form>
              <form method="POST" action="main.php">
                <label>Search by Meal Type: <select name="categories">
                  <option value="All Recipes">All Recipes</option>
                  <?php
                  $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

                  if (!$mysqli || $mysqli->connect_errno) {
                      echo "Error connection to MySQLi Session(".$mysqli->connect_errno."): ".$mysqli->connect_error;
                  }

                  $display_categories = "SELECT DISTINCT M.type FROM meal_type M INNER JOIN recipes R ON R.meal_type = M.mid WHERE R.username = '".$username."' ORDER BY M.type ASC";

                  if ($all = $mysqli->query($display_categories)) {
                    while ($row = $all->fetch_row()) {
                      echo '<option name="categories" value="'.$row[0].'">'.$row[0].'</option>';
                    }
                  }
                  $all->close();
                  ?>
                </select> <input type="submit" value="Search"></form></label>

              <form method="POST" action="main.php">
                <input name="search" class="form-control" type="text" placeholder="Search by Star Ingredients">
                <input type="submit" name="searchByIngredient" value="Search">
              </form>
<br><br><br><br><br>

          <div class="share-widget hidden-xs hidden-sm">

          </div>
    </div></div>
    </article>
  </section>
    <?php include("sidebar.php"); ?>
</div>
</div>
</main>

<?php include("footer.php"); ?>

<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jQuery.scrollSpeed.js"></script>
<script>
  $(function() {
    jQuery.scrollSpeed(100, 1000);
  });
</script>
</body>
</html>
