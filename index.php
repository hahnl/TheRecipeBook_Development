<?php
session_start();

include_once 'connect.php';

if(isset($_SESSION['user'])) {
 header("Location: main.php?page=1");
}

if (isset($_POST['login_button'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (!($stmt = $mysqli->prepare("SELECT username, password FROM users WHERE username = ?"))) {
    echo "Prepared statement fail: (".$mysqli->errno.")".$mysqli->error;
  }

  if (!$stmt->bind_param('s', $username)) {
    echo "Binding paramaters fail:(".$stmt->errno.")".$stmt->error;
  }

  if (!$stmt->execute()) {
    echo "Execute fail: (".$stmt->errno.")".$stmt->error;
  }

  $userdata = $stmt->get_result();
  $row = $userdata->fetch_row();
  $hash = $row[1];

  if (password_verify($password,$hash)) {
    $_SESSION['user'] = $_POST['username'];
    header('Location: main.php?page=1');
    exit();
  }
  else {
    ?>
      <script>
        alert("Sorry, we do not recognize that username and password combination. Please try again.");
      </script>

    <?php
  }

  $stmt->close();
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

  <title>The Recipe Book - Login</title>

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
                      <form class="form-horizontal" role="form" method="post" action="index.php">
                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-3 control-label">
                              Username</label>
                          <div class="col-sm-9">
                              <input name="username" type="text" class="form-control" id="inputEmail3" placeholder="Username" required>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="inputPassword3" class="col-sm-3 control-label">
                              Password</label>
                          <div class="col-sm-9">
                              <input name="password" type="password" class="form-control" id="inputPassword3" placeholder="Password" required>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-offset-3 col-sm-9">
                              <div class="checkbox">
                                  <label>
                                      <input type="checkbox"/>
                                      Remember me
                                  </label>
                              </div>
                          </div>
                      </div>
                      <div class="form-group last">
                          <div class="col-sm-offset-3 col-sm-9">
                              <input type="submit" class="btn btn-success btn-sm" name="login_button" value="Sign in">
                                   <button type="reset" class="btn btn-default btn-sm">
                                  Reset</button>
                          </div>
                      </div>
                      </form>
                  </div>
                  <div class="panel-footer">
                      Not registered? <a href="register.php">Register here</a></div>
              </div>
          </div>
      </div>
  </div>

</body>
</html>
