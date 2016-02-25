<?php
   $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

   if (!$mysqli || $mysqli->connect_errno) {
     echo "Error connection to MySQLi Session(".$mysqli->connect_errno."): ".$mysqli->connect_error;
   }
   $feature_recipe_id = 40;
   $feature_query = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, (SELECT favorite FROM favs WHERE username = '".$username."' && recipe_id = R.id), R.username, DATE_FORMAT(R.date, '%d-%M-%Y'), R.url, (SELECT coalesce(ROUND(AVG(RT.rating)), 0) FROM rating RT WHERE RT.recipe_id = R.id), R.desc FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid WHERE R.id = '".$feature_recipe_id."' ORDER BY R.name ASC";
   $feature_result = $mysqli->query($feature_query);
   $feature_row = $feature_result->fetch_row();
?>
<div class="row">
  <aside class="col-md-4 col-sm-8 col-xs-8">
  <div class="sidebar">
    <h4 class="text-center">This Week's Featured Recipe</h4>
    <a href="recipe.php?id=<?php echo $feature_row[0]; ?>">
      <img src="recipe_img/<?php echo $feature_row[10]; ?>" class="img-thumbnail img-responsive center-block" width="250" alt="Recipe Photo">
    </a>
    <h4 class="text-center">
      <a href="recipe.php?id=<?php echo $feature_row[0]; ?>"> <?php echo $feature_row[1]; ?>
    </a></h4>
    <h6 class="text-center"> Avg. Rating: <?php echo $feature_row[11]; ?> Stars</h6>
    <hr>
    <h4 class="text-center">Get Our Featured Recipe by Email</h4>
      <div class="input-group margin-bottom-sm">
      <input class="form-control" type="text" placeholder="Your Email">
        <a href="#" class="input-group-addon"><i class="fa fa-paper-plane fa-fw"></i></a>
      </div>
  </div>
  </div>
  </aside>
</div>
