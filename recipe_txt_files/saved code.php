<?php
   $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

   if (!$mysqli || $mysqli->connect_errno) {
     echo "Error connection to MySQLi Session(".$mysqli->connect_errno."): ".$mysqli->connect_error;
   }

   if ($filter != 'All Recipes') {
       $filtering = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite, R.username FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid WHERE M.type = `.$filter.` ORDER BY R.name ASC";
   }
   else {
       $filtering = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite, R.username FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid ORDER BY R.name ASC";
   }

   if ($search != 'No Search') {
       $filtering = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite, R.username FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid WHERE R.main_ingredient_1 = `.$search.` OR R.main_ingredient_2 = `.$search.` ORDER BY R.name ASC";
   }

   if ($pantrySearch != 'Not Pantry') {
       $filtering = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite, R.username FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid WHERE R.main_ingredient_1 = `.$pantrySearch.` OR R.main_ingredient_2 = `.$pantrySearch.` ORDER BY R.name ASC";
   }

   if ($cookingTime != 'Not Time') {
       $filtering = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite, R.username FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid WHERE R.cooking_time <= `.$cookingTime.` ORDER BY R.cooking_time ASC";
   }

   $dbTable = $mysqli->query($filtering);
   if ($dbTable->num_rows > 0) {
     while ($row = $dbTable->fetch_row()) {
       if ($row[7] === '1') {
         $status = "heart";
       }
       elseif ($row[7] === '0') {
         $status = ' ';
       }
       $idNum = $row[0];
       ?>
<article class="blog-item">
  <div class="row">
    <div class="col-md-3">
      <a href="#">
        <img src="images/restaurant.jpg" class="img-thumbnail center-block" alt="Recipe Photo Uploaded">
      </a>
    </div>
    <div class="col-md-9">
      <p>in <a href="#"><?php echo $row[2]; ?></a>, posted by <a href="#"><?php echo $row[8]; ?></a> <time>23-july-2015<time></p>
      <h1>
        <a href="#">
        <?php
          if ($row[7]) === '1') {
            echo $status;
          }
          echo $row[1];
        ?>
      </a></h1>
      <p> Rating: 5 Stars   - Cooking Time: <?php echo $row[3]; ?> minutes <br>
        Star Ingredients: <?php echo $row[4]; ?>, <?php echo $row[5]; ?> <br>
        Brief description... 150 words or less? 60 words or less? </p>
    </div>
  </div>
</article>
<?php
}
}
?>




            echo "<tr><td>".$row[1]."</td><td>"</td><td>".$row[3]." minutes</td><td><br>";
            $thing = $row[6];
            $output = str_replace(array("\r\n", "\n", "\r", "\\r\\n", "\\n"), '<br>', $thing);
            $new_output = str_replace("\\", '', $output);
            echo "<table class=\"recipe\"><tr><td>".$new_output."</td></tr></table>";
            echo "</td>";
            if ($row[7] === '0') {
              echo "<td>".$status."<form action='action.php' method='POST'><input type='hidden' name='id' value='$idNum'><input type='submit' name='favorite' value='Make Favorite ✔'></form></td>";
            }
            elseif ($row[7] === '1'){
              echo "<td><form action='action.php' method='POST'><img src='http://web.engr.oregonstate.edu/~hahnl/final_project/images/bullet-main.png'>".$status." <input type='hidden' name='id' value='$idNum'><input type='submit' name='unfav' value='Undo'></form></td>";
            }
            echo "<form action='action.php' method='POST'><input type='hidden' name='id' value='$idNum'><td><input type='submit' name='remove' value='✖'></form></td>";
          }
        }
      ?>
      </table>
