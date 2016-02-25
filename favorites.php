<?php
error_reporting(E_ALL);
ob_start();
session_start();

include_once 'connect.php';

if(!isset($_SESSION['user'])) {
 header("Location: index.php");
}

$username = $mysqli->real_escape_string($_SESSION['user']);
$num_rec_per_page = 10;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
$previous_page = $page - 1;
$next_page = $page + 1;
$start_from = ($page-1) * $num_rec_per_page;
$sql = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, (SELECT favorite FROM favs WHERE username = '".$username."' && recipe_id = R.id), R.username, DATE_FORMAT(R.date, '%d-%M-%Y'), R.url, (SELECT coalesce(ROUND(AVG(RT.rating)), 0) FROM rating RT WHERE RT.recipe_id = R.id), R.desc FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid INNER JOIN favs F ON F.recipe_id = R.id WHERE F.username = '".$username."' && F.recipe_id = R.id && F.favorite = 1 ORDER BY R.name ASC LIMIT $start_from, $num_rec_per_page";
$rs_result = $mysqli->query($sql); //run the query

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

    <?php
       $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

       if (!$mysqli || $mysqli->connect_errno) {
         echo "Error connection to MySQLi Session(".$mysqli->connect_errno."): ".$mysqli->connect_error;
       }

       if ($filter != 'All Recipes') {
           $filtering = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite, R.username FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid WHERE M.type = `$filter` ORDER BY R.name ASC";
       }
       else {
           $filtering = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite, R.username, DATE_FORMAT(R.date, '%d-%M-%Y'), R.url, (SELECT coalesce(ROUND(AVG(RT.rating)), 0) FROM rating RT WHERE RT.recipe_id = R.id), R.desc FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid ORDER BY R.name ASC";
       }

       if ($search != 'No Search') {
           $filtering = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite, R.username FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid WHERE R.main_ingredient_1 = `.$search.` OR R.main_ingredient_2 = `.$search.` ORDER BY R.name ASC";
       }

       if ($pantrySearch != 'Not Pantry') {
           $filtering = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite, R.username FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid WHERE R.main_ingredient_1 = `.$pantrySearch.` OR R.main_ingredient_2 = `.$pantrySearch.` ORDER BY R.name ASC";
       }

       if ($cookingTime != 'Not Time') {
           $filtering = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite, R.username, R.url FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid WHERE R.cooking_time <= `.$cookingTime.` ORDER BY R.cooking_time ASC";
       }

       //$dbTable = $mysqli->query($filtering);
       //if ($dbTable->num_rows > 0) {
         //while ($row = $dbTable->fetch_row()) {
       if ($rs_result->num_rows > 0) {
         while ($row = $rs_result->fetch_row()) {
           if ($row[7] === '1') {
             $status = "<span class=\"glyphicon glyphicon-heart\"></span> Favorite";
           }
           elseif ($row[7] === '0') {
             $status = ' ';
           }
           $idNum = $row[0];
           ?>
    <article class="blog-item">
      <div class="row">
      <div class="cmnt-clipboard"><?php
      if ($row[7] === '1') {
        echo "<span class=\"btn-clipboard\">".$status."</span>";
      }
      ?></div></div>
      <div class="row">
        <div class="col-md-3">
          <a href="recipe.php?id=<?php echo $row[0]; ?>">
            <img src="recipe_img/<?php echo $row[10]; ?>" class="img-thumbnail center-block" alt="Recipe Photo">
          </a>
        </div>
        <div class="col-md-9">
          <p>In <a href="#"><?php echo $row[2]; ?></a>, Posted by <a href="#"><?php echo $row[8]; ?></a> <time><?php echo $row[9]; ?><time></p>
          <h1>
            <a href="recipe.php?id=<?php echo $row[0]; ?>"> <?php echo $row[1]; ?>
          </a></h1>
          <p> Avg. Rating: <?php echo $row[11]; ?> Stars   <a href="recipe.php?id=<?php echo $row[0]; ?>#disqus_thread">Comments</a> <br>
            Cooking Time: <?php echo $row[3]; ?> minutes <br>
            Star Ingredients: <?php echo $row[4]; ?>, <?php echo $row[5]; ?> <br>
            <i><?php echo $row[12]; ?></i> </p>
        </div>
      </div>
    </article>
    <?php
  }
}
?>

<?php
$sql = "SELECT R.id, R.name, M.type, R.cooking_time, R.main_ingredient_1, R.main_ingredient_2, R.recipe, R.favorite, R.username, DATE_FORMAT(R.date, '%d-%M-%Y'), R.url, (SELECT coalesce(ROUND(AVG(RT.rating)), 0) FROM rating RT WHERE RT.recipe_id = R.id), R.desc FROM recipes R INNER JOIN meal_type M ON R.meal_type = M.mid WHERE R.favorite = 1 ORDER BY R.name ASC";
$rs_result = $mysqli->query($sql);
$total_records = $rs_result->num_rows;  //count number of records
$total_pages = ceil($total_records / $num_rec_per_page);
?>
    <div class="page-turn">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 text-center">
      <nav>
        <ul class="pagination pagination-sm">
          <?php
          if ($page === '1') { ?>
            <li class="disabled"><a href="" aria-label="Previous"><span aria-hidden="true">Previous</span></a></li>
          <?php }
          else { ?>
            <li><a href="favorites.php?page=<?php echo $previous_page; ?>">Previous</a></li>
          <?php }
          for ($i=1; $i<=$total_pages; $i++) {
            echo "<li><a href='favorites.php?page=".$i."'>".$i."</a></li>";
          }
          ?>
         <?php
         if ($page >= $total_pages) { ?>
           <li class="disabled"><a href="" aria-label="Next"><span aria-hidden="true">Next</span></a></li>
         <?php } else { ?>
           <li><a href="favorites.php?page=<?php echo $next_page; ?>" aria-label="Next"><span aria-hidden="true">Next</span></a></li>
         <?php } ?>
        </ul>
      </nav>
      </div>
    </div>
    </div>
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

<script type="text/javascript">
    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = 'the-recipe-book';

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function () {
        var s = document.createElement('script'); s.async = true;
        s.type = 'text/javascript';
        s.src = '//' + disqus_shortname + '.disqus.com/count.js';
        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());
</script>

</body>
</html>
