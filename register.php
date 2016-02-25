<?php
session_start();

include_once 'connect.php';

if (isset($_POST['register_button'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $pword = $_POST['password'];
  $location = $_POST['location'];
  $occupation = $_POST['occupation'];
  $password = password_hash($pword, PASSWORD_BCRYPT);

  if (!($stmt = $mysqli->prepare("INSERT INTO `users`(`username`, `email`, `password`, `location`, `occupation`) VALUES (?,?,?,?,?)"))) {
    echo "Prepare failed: (".$mysqli->errno.")".$mysqli->error;
  }

  if (!$stmt->bind_param('sssss', $username, $email, $password, $location, $occupation)) {
    echo "Binding paramaters failed".$stmt->errno.")".$stmt->error;
  }

  if (!$stmt->execute()) {
    ?>
        <script>
          alert('Sorry, that username is already taken. Please try again.');
        </script>
    <?php
  } else {
    ?>
        <script>
          alert('Registration successful.');
        </script>
    <?php
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
  }
}

$mysqli->close();
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

  <title>The Recipe Book - Registration</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/animate.css">
  <link rel="stylesheet" href="css/index_style.css" type="text/css">
</head>
<body>
  <div class="container">
      <div class="row">
          <div class="col-md-4 col-md-offset-7">
              <div class="panel panel-default">
                  <div class="panel-heading">
                      <img src="images/Logo.png" alt-="The Recipe Book Logo"></div>
                  <div class="panel-body">
                      <form class="form-horizontal" role="form" method="post" action="register.php">
                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-3 control-label">
                              Username</label>
                          <div class="col-sm-9">
                              <input name="username" type="text" class="form-control" id="inputEmail3" placeholder="Username" required>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="inputPassword3" class="col-sm-3 control-label">
                              Email</label>
                          <div class="col-sm-9">
                              <input name="email" type="email" class="form-control" id="inputPassword3" placeholder="Email" required>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="inputPassword3" class="col-sm-3 control-label">
                              Password</label>
                          <div class="col-sm-9">
                              <input name="password" type="password" class="form-control" id="inputPassword3" placeholder="Password" required>
                          </div>
                      </div>
                      <hr>
                      <div class="form-group">
                          <label for="location" class="col-sm-3 control-label">
                              Location</label>
                          <div class="col-sm-9">
                              <input name="location" type="text" class="form-control" id="inputPassword3" placeholder="City, State">
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="occupation" class="col-sm-3 control-label">
                              Occupation</label>
                          <div class="col-sm-9">
                              <input name="occupation" type="text" class="form-control" id="inputPassword3" placeholder="Job Title or Interest">
                          </div>
                      </div>
                      <div class="form-group last">
                          <div class="col-sm-offset-3 col-sm-9">
                              <input type="submit" class="btn btn-success btn-sm" name="register_button" value="Register">
                                   <button type="reset" class="btn btn-default btn-sm">
                                  Reset</button>
                          </div>
                      </div>
                      </form>
                  </div>
                  <div class="panel-footer">
                      Already registered? <a href="index.php">Sign in here</a></div>
              </div>
          </div>
      </div>
  </div>
</body>
</html>
