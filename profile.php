<?php
error_reporting(E_ALL);
ob_start();
session_start();

include_once 'connect.php';

if(!isset($_SESSION['user'])) {
 header("Location: index.php");
}

$username = $mysqli->real_escape_string($_SESSION['user']);
$user = $mysqli->real_escape_string($_GET['user']);
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
    <?php
     $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

     if (!$mysqli || $mysqli->connect_errno) {
       echo "Error connection to MySQLi Session(".$mysqli->connect_errno."): ".$mysqli->connect_error;
     }

    $filtering = "SELECT U.username, U.email, U.password, U.location, U.occupation FROM users U WHERE U.username = '".$user."'";
    $dbTable = $mysqli->query($filtering);

    if ($dbTable->num_rows > 0) {
       while ($row = $dbTable->fetch_row()) {
         $userN = $row[0];
         ?>
      <div class="row">
  		<div class="col-md-3">
  			<div class="profile-sidebar">
          <?php
          $pull = "SELECT username, url, lastUpload FROM users WHERE username = '".$user."'";

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

              if (file_exists("upload/" . $_FILES["file"]["name"]))
                {
                unlink("upload/" . $_FILES["file"]["name"]);
                }
              else
                {
                    $pic=$_FILES["file"]["name"];
                      $conv=explode(".",$pic);
                      $ext=$conv['1'];

                move_uploaded_file($_FILES["file"]["tmp_name"],
                "upload/". $user.".".$ext);
                echo "Stored in as: " . "upload/" . $user.".".$ext;
                $url=$user.".".$ext;

                if (!($stmt = $mysqli->prepare("UPDATE users SET url = ? WHERE username = ?"))) {
                  echo "Prepare failed: (".$mysqli->errno.")".$mysqli->error;
                }

                if (!$stmt->bind_param('ss', $url, $user)) {
                  echo "Binding paramaters failed".$stmt->errno." ".$stmt->error;
                }

                if (!$stmt->execute()) {
                  echo "Failed to execute.";
                }
                else {
                  echo "Saved to database! YES!";
                }
                }
              }
            }
          else
            {
            echo "File Size Limit Crossed 200 KB Picture Size less than 200 KB";
            }
          }
          $res = $mysqli->query($pull);
          $pics = $res->fetch_assoc();
         ?>
  				<!-- SIDEBAR USERPIC -->
  				<div class="profile-userpic">
             <img src="upload/<?php echo $pics[url]; ?>" class="img-responsive" alt="Profile Pic">
  				</div>
  				<!-- END SIDEBAR USERPIC -->
  				<!-- SIDEBAR USER TITLE -->
  				<div class="profile-usertitle">
  					<div class="profile-usertitle-name"><?php echo $row[0]; ?></div>
  					<div class="profile-usertitle-job">
              <?php echo $row[4]; ?>
  					</div>
  				</div>
  				<!-- END SIDEBAR USER TITLE -->
  				<!-- SIDEBAR BUTTONS -->
  				<div class="profile-userbuttons">
  					<button type="button" class="btn btn-success btn-sm">Follow</button>
  					<button type="button" class="btn btn-danger btn-sm">Message</button>
  				</div>
  				<!-- END SIDEBAR BUTTONS -->
  				<!-- SIDEBAR MENU -->
  				<div class="profile-usermenu">
  					<ul class="nav">
  						<li class="active">
  							<a href="#">
  							<i class="glyphicon glyphicon-home"></i>
  							Overview </a>
  						</li>
  						<li>
  							<a href="#">
  							<i class="glyphicon glyphicon-user"></i>
  							Account Settings </a>
  						</li>
  						<li>
  							<a href="#" target="_blank">
  							<i class="glyphicon glyphicon-ok"></i>
  							Tasks </a>
  						</li>
  						<li>
  							<a href="#">
  							<i class="glyphicon glyphicon-flag"></i>
  							Help </a>
  						</li>
  					</ul>
  				</div>
  				<!-- END MENU -->
  			</div>
  		</div>
  		<div class="col-md-9">
              <div class="profile-content">
                Profile Contents
              </div>
  		</div>
  	</div>
  </div>
  </div>
       <?php

          }
        }

      ?>
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
