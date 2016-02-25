<?php
error_reporting(E_ALL);
ob_start();
session_start();

include_once 'connect.php';

$username = $_GET["username"];

?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $username ?>'s Favorite Recipes - Your Personal Cookbook</title>
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
  <table class="menu">
    <tr>
      <td background="http://web.engr.oregonstate.edu/~hahnl/final_project/images/left-side-banner.png" width="163px">
        <div align="center">
          <br><br><br><br><br><br><br>
          <?php
             echo "<b>".$username."'s Favorites</b>!";
          ?>
        </div>
      </td>
      <td background="http://web.engr.oregonstate.edu/~hahnl/final_project/images/background_image.png">
        <div align="left">
        </div>
      </td>
      <td background="http://web.engr.oregonstate.edu/~hahnl/final_project/images/right-side-banner.png" width="165px">
        <div align="center">
          <br><br><br>
          <button onClick="window.print()">Print Favorite Recipes</button>
        </div>
      </td>
    </tr>
    </div>
  </table>
  <table class="main-table">
    <tr>
      <td><h3>Recipe Name</h3></td><td><h3>Meal Type</h3></td><td><h3>Cooking Time</h3></td><td width="50%"><h3>Recipe Instructions</h3></td>
    </tr>
  <?php
    $filtering = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid WHERE favorite = '1' && R.username = '".$username."' ORDER BY R.name ASC";

    $dbTable = $mysqli->query($filtering);
    if ($dbTable->num_rows > 0) {
      while ($row = $dbTable->fetch_row()) {
        echo "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]." minutes</td><td><b>★ Main Ingredient:</b> ".$row[4]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Secondary Ingredient:</b> ".$row[5]."<br>";
        $thing = $row[6];
        $output = str_replace(array("\r\n", "\n", "\r", "\\r\\n", "\\n"), '<br>', $thing);
        $new_output = str_replace("\\", '', $output);
        echo "<table class=\"recipe\"><tr><td>".$new_output."</td></tr></table>";
        echo "</td>";
      }
    }
  ?>
</table>
<br><br>
<div align="center"> Don't have a Personal Cookbook? <button onclick="window.location.href='register.php'">Register Now</button>
<br><br>
</div>
</body>
</html>
