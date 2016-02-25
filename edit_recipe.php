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

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if (!$mysqli || $mysqli->connect_errno) {
  echo "Error connection to MySQLi Session(".$mysqli->connect_errno."): ".$mysqli->connect_error;
}

$test = "SELECT recipes.username FROM recipes WHERE recipes.id = '".$recipe_id."'";
$returning = $mysqli->query($test);
if ($returning->num_rows > 0) {
  while ($testing = $returning->fetch_row()) {
    if ($testing[0] === $username) {
      echo "";
    } else {
      header("Location: main.php");
    }
  }
}

if (!isset($_POST['rate'])) {
  $query = "SELECT rating FROM rating WHERE recipe_id = '".$recipe_id."' AND username = '".$username."'";
  $final = $mysqli->query($query);
  $col = $final->fetch_assoc();
  if ($final->num_rows > 0) {
    $load_rating = $col['rating'];
  } else {
    $load_rating = "0";
  }
}

if (isset($_POST['rate']) && !empty($_POST['rate'])) {
    $rate = $mysqli->real_escape_string($_POST['rate']);
    $sql = "SELECT id FROM rating WHERE recipe_id = '".$recipe_id."' AND username = '".$username."'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
    if ($result->num_rows > 0) {
      $sql = "UPDATE rating SET rating = '".$rate."' WHERE recipe_id = '".$recipe_id."' AND username = '".$username."'";
      if (mysqli_query($mysqli, $sql)) {
          echo $row['id'];
      }
    } else {
        $sql = "INSERT INTO rating (rating, recipe_id, username) VALUES ('".$rate."', '".$recipe_id."', '".$username."')";
        if (mysqli_query($mysqli, $sql)) {
            echo "0";
        }
    }
}

if (!isset($_POST["categories"])) {
  $filter = 'All Recipes';
}
else {
  $filter = $_POST["categories"];
}
if (!isset($_POST["search"])) {
  $search = 'No Search';
}
else {
  $search = $_POST["search"];
}
if (!isset($_POST["searchByPantryItem"])) {
  $pantrySearch = 'Not Pantry';
}
else {
  $pantrySearch = $_POST["searchByPantryItem"];
}
if (!isset($_POST["cooking_time"])) {
  $cookingTime = 'Not Time';
}
else {
  $cookingTime = $_POST["cooking_time"];
}
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
  <script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/jQuery.scrollSpeed.js"></script>

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

    <?php
       $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

       if (!$mysqli || $mysqli->connect_errno) {
         echo "Error connection to MySQLi Session(".$mysqli->connect_errno."): ".$mysqli->connect_error;
       }

       $filtering = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite, R.username, DATE_FORMAT(R.date, '%d-%M-%Y'), R.url, (SELECT coalesce(ROUND(AVG(RT.rating)), 0) FROM rating RT WHERE RT.recipe_id = R.id), R.desc FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid WHERE R.id = '".$recipe_id."' ORDER BY R.name ASC";

       $dbTable = $mysqli->query($filtering);
       if ($dbTable->num_rows > 0) {
         while ($row = $dbTable->fetch_row()) {
           if ($row[7] === '1') {
             $status = "<span class=\"glyphicon glyphicon-heart\"></span> Favorite";
           }
           elseif ($row[7] === '0') {
             $status = ' ';
           }
           ?>
    <article class="recipe-blog-item">
      <div class="row">
        <div class="col-md-3">
          <a href="recipe.php?id=<?php echo $row[0]; ?>">
            <img src="recipe_img/<?php echo $row[10]; ?>" class="img-thumbnail center-block" alt="Recipe Photo">
          </a>
        </div>
        <div class="col-md-9">
          <ul class="nav navbar-nav navbar-right pull-right">
           <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Options</a>
              <ul class="dropdown-menu">
                <script>
                  function makeFavorite() {
                    $.get("makeFavorite.php?id=<?php echo $row[0]; ?>");
                    setTimeout(function() {
                      window.location.href="recipe.php?id=<?php echo $row[0]; ?>";
                    }, 1000);
                  }
                  function unFavorite() {
                    $.get("unFavorite.php?id=<?php echo $row[0]; ?>");
                    setTimeout(function() {
                      window.location.href="recipe.php?id=<?php echo $row[0]; ?>";
                    }, 1000);
                  }
                  function deleteRecipe() {
                    if (confirm("Delete Recipe?\nThis action cannot be undone.")) {
                      $.get("delete.php?id=<?php echo $row[0]; ?>");
                      setTimeout(function() {
                        window.location.href="main.php";
                      }, 1000);
                    } else {
                      return false;
                    }
                  }
                  function editRecipe() {
                    setTimeout(function() {
                      window.location.href="edit_recipe.php?id=<?php echo $row[0]; ?>";
                    }, 1000);
                  }
                  function updatePhoto() {
                    setTimeout(function() {
                      window.location.href="addPhoto.php?id=<?php echo $row[0]; ?>";
                    }, 1000);
                  }
                  function shareRecipe() {
                    setTimeout(function() {
                      window.location.href="share.php?id=<?php echo $row[0]; ?>";
                    }, 1000);
                  }
                </script>
                <?php
                if ($row[7] === '0') { ?>
                <li><a href="#" onclick="makeFavorite();"><span class="glyphicon glyphicon-heart"></span> Favorite</a></li>
                <?php } else { ?>
                <li><a href="#" onclick="unFavorite();"><span class="glyphicon glyphicon-remove"></span> Unfavorite</a></li>
                <?php }  ?>
                <li><a href="#" onclick="shareRecipe();"><span class="glyphicon glyphicon-share-alt"></span> Share</a></li>
                <?php
                if ($row[8] === $username) { ?>
                <li><a href="#" onclick="updatePhoto();"><span class="glyphicon glyphicon-camera"></span> Update Photo</a></li>
                <li><a href="#" onclick="editRecipe();"><span class="glyphicon glyphicon-pencil"></span> Edit Recipe</a></li>
                <li><a href="#" onclick="deleteRecipe();"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                  <?php              } ?>
              </ul>
           </li>
          </ul>
          <p>In <a href="#"><?php echo $row[2]; ?></a>, Posted by <a href="#"><?php echo $row[8]; ?></a> <time><?php echo $row[9]; ?><time>
          </p>
          <h1>
            <a href="recipe.php?id=<?php echo $row[0]; ?>"> <?php echo $row[1]; ?>
          </a> <a href="#" onclick="editTitle();"><span class="glyphicon glyphicon-pencil"></span></a>
        </h1>
          <p> Avg. Rating: <?php echo $row[11]; ?> Stars - Cooking Time: <?php echo $row[3]; ?> minutes <a href="#" onclick="editTime();"><span class="glyphicon glyphicon-pencil"></span></a><br>
            Star Ingredients: <?php echo $row[4]; ?> <a href="#" onclick="editFirstIng();"><span class="glyphicon glyphicon-pencil"></span></a>, <?php echo $row[5]; ?> <a href="#" onclick="editSecondIng();"><span class="glyphicon glyphicon-pencil"></span></a><br>
            <i><?php echo $row[12]; ?></i> <a href="#" onclick="editDesc();"><span class="glyphicon glyphicon-pencil"></span></a></p>
            <?php
            $thing = $row[6];
            $output = str_replace(array("\r\n", "\n", "\r", "\\r\\n", "\\n"), '<br>', $thing);
            $new_output = str_replace("\\", '', $output);
            echo "<div class=\"well\">".$new_output."</div>";
            ?>
            <script>
                $(document).ready(function () {
                    $("#rated .stars").click(function () {
                        $.post('recipe.php?id=<?php echo $recipe_id; ?>',{rate:$(this).val()});
                        $(this).attr("checked");
                    });
                    $('#star<?php echo $load_rating; ?>').trigger('click');
                });
            </script>
            Rate This Recipe: <br>
            <fieldset id='rated' class="rating">
                <input class="stars" type="radio" id="star5" name="rating" value="5" />
                <label class = "full" for="star5" title="Awesome - 5 stars"></label>
                <input class="stars" type="radio" id="star4" name="rating" value="4" />
                <label class = "full" for="star4" title="Pretty good - 4 stars"></label>
                <input class="stars" type="radio" id="star3" name="rating" value="3" />
                <label class = "full" for="star3" title="Meh - 3 stars"></label>
                <input class="stars" type="radio" id="star2" name="rating" value="2" />
                <label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
                <input class="stars" type="radio" id="star1" name="rating" value="1" />
                <label class = "full" for="star1" title="Sucks big time - 1 star"></label>

            </fieldset>
        </div>
      </div>
    </article>
    <?php
  }
}
?>
</div>


<div id="disqus_thread"></div>
<script type="text/javascript">
    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = 'the-recipe-book';

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
</div>
</main>

<?php include("footer.php"); ?>

<script>
  $(function() {
    jQuery.scrollSpeed(100, 1000);
  });
</script>

</body>
</html>
