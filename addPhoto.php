<?php
error_reporting(E_ALL);
ob_start();
session_start();

include_once 'connect.php';

if(!isset($_SESSION['user'])) {
 header("Location: index.php");
}

$username = $mysqli->real_escape_string($_SESSION['user']);
$recipe_id = $_GET["id"];
echo $recipe_id;

$pull = "SELECT username, url FROM recipes WHERE id = '".$recipe_id."'";

$allowedExts = array("jpg", "jpeg", "gif", "png","JPG");
$extension = @end(explode(".", $_FILES["file"]["name"]));
if(isset($_POST['pupload'])) {
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/JPG")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "image/pjpeg"))
&& ($_FILES["file"]["size"] < 200000000)
&& in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {
    echo '<div class="plus">';
    echo "Uploaded Successully";
    echo '</div>';

    echo"<br/><b><u>Image Details</u></b><br/>";

    echo "Name: " . $_FILES["file"]["name"] . "<br/>";
    echo "Type: " . $_FILES["file"]["type"] . "<br/>";
    echo "Size: " . ceil(($_FILES["file"]["size"] / 1024)) . " KB";

    if (file_exists("recipe_img/" . $_FILES["file"]["name"]))
      {
      unlink("recipe_img/" . $_FILES["file"]["name"]);
      }
    else
      {
          $pic=$_FILES["file"]["name"];
            $conv=explode(".",$pic);
            $ext=$conv['1'];

      $filename = $username . $recipe_id;
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "recipe_img/". $filename.".".$ext);
      echo "Stored in as: " . "recipe_img/" . $filename.".".$ext;
      $url=$filename.".".$ext;

      chmod("recipe_img/$url",0755);

      if (!($stmt = $mysqli->prepare("UPDATE recipes SET url = ? WHERE id = ?"))) {
        echo "Prepare failed: (".$mysqli->errno.")".$mysqli->error;
      }

      if (!$stmt->bind_param('ss', $url, $recipe_id)) {
        echo "Binding paramaters failed".$stmt->errno." ".$stmt->error;
      }

      if (!$stmt->execute()) {
        echo "Failed to execute.";
      }
      else {
        echo "Saved to database! YES!";
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=main.php?page=1\">";
      }
      }
    }
  }
else
  {
  echo "File Size Limit Crossed 200 KB Picture Size less than 200 KB";
  }
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
    <article class="blog-item">
      <div class="row">
        <div class="col-md-12">
        <p> Under Construction. Please upload a photo to go with your recipe! </p>
        <form action="addPhoto.php?id=<?php echo $recipe_id; ?>" method="post" enctype="multipart/form-data">
          <?php
            $res = $mysqli->query($pull);
            $pics = $res->fetch_assoc();
            echo "<img src='recipe_img/".$pics[url]."' alt='Recipe Image' class='img-responsive'/>";
            ?>

            <input type="file" name="file">
            <input type="submit" name="pupload" class="button" value="Upload">
        </form>
    </div>
  </div>
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
