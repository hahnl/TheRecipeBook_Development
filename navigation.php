<nav class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">
<div class="container">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarCollapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand pull-left" href="index.php"><img src="images/Logo.png" alt-="The Recipe Book Logo"></a>
  </div>

  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="nav navbar-nav">
      <li class="active"><a href="main.php?page=1"><span class="glyphicon glyphicon-cutlery"></span>  Recipes</a></li>
      <li><a href="profile.php?user=<?php echo $username; ?>"><span class="glyphicon glyphicon-user"></span> Chef</a></li>
      <li><a href="pantry.php"><span class="glyphicon glyphicon-shopping-cart"></span> Pantry</b></a></li>
      <li><a href="search.php"><span class="glyphicon glyphicon-search"></span> Search</a></li>
      <li><a href="addRecipe.php"><span class="glyphicon glyphicon-plus"></span> Add Recipe</a></li>
      <li><a href="favorites.php?page=1"><span class="glyphicon glyphicon-heart"></span> Favorite Recipes</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="logout.php?logout"><span class="glyphicon glyphicon-lock"></span> Logout</a></li>
    </ul>
  </div>
</div>
</nav>
