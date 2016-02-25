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
        <p> Under Construction </p>
        <div align="center">
          <form method="POST" action="action.php" enctype="multipart/form-data">
            <label><input type="text" name="pantry_ingredient_add"></label>
            <input type="submit" value="Add Ingredient to Pantry" name="addToPantry">
          </form>
        </div>
      <table>
          <tr>
            <td><h3>Pantry Ingredient</h3></td><td><h3>Update?</h3></td><td><h3>Remove</h3></td>
          </tr>
        <?php
          $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

          if (!$mysqli || $mysqli->connect_errno) {
            echo "Error connection to MySQLi Session(".$mysqli->connect_errno."): ".$mysqli->connect_error;
          }

        $filtering = "SELECT P.pid, P.p_ingredient FROM pantry P WHERE P.username = '".$username."' ORDER BY P.p_ingredient ASC";
        $dbTable = $mysqli->query($filtering);

        if ($dbTable->num_rows > 0) {
            while ($row = $dbTable->fetch_row()) {
              $idNum = $row[0];
              echo "<tr><td>".$row[1]."</td>";
              echo "<form action='action.php' method='POST'><input type='hidden' name='id' value='$idNum'><td><input type='text' name='editIngPantry' value='".$row[1]."'><input type='submit' name='editPantry' value='Edit'></form></td>";
              echo "<form action='action.php' method='POST'><input type='hidden' name='id' value='$idNum'><td><input type='submit' name='removeFromPantry' value='✖'></form></td></tr>";
            }
          }
        ?>
      </table>
      <br><br><br><br><br><br><br><br><br><br>
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
