<?php
error_reporting(E_ALL);
ob_start();
session_start();

include_once 'connect.php';

if(!isset($_SESSION['user'])) {
 header("Location: index.php");
}

$mobile = "No";
$username = $mysqli->real_escape_string($_SESSION['user']);
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
  $mobile = "Yes";
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

  <script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/jQuery.scrollSpeed.js"></script>
  <script type="text/javascript" src="js/typeahead.bundle.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
    $(function(){
      $('input[name="hidden_ingredient"]').click(function(){
        var groupCSS = '<span class="glyphicon glyphicon-pushpin"></span> <input type=\"text\"  class=\"input-lg\" size=\"8\" placeholder=\"Quantity\">&nbsp; ';
        var new_ingredient = $('#search_ingredients').val();
        var div = document.createElement('div');
        div.style.textTransform ="capitalize";
        div.innerHTML = groupCSS;
        document.getElementById("Ingredient").appendChild(div);
        div.appendChild(document.createTextNode(new_ingredient));


      });

     $('#search_ingredients').keypress(function (e) {
     var key = e.which;
     if(key == 13)
      {
        $('input[name = hidden_ingredient]').click();
        $('#search_ingredients').val('');
        return false;
      }
    });
    });


  var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });

    cb(matches);
  };
};

var ings = ['Apple','Orange','Banana','Peach','Kiwi','Onion','Potato','Leek','Shallot',
              'Green Onion','Red Pepper','Green Pepper','Jalepeno','Squash','Roast','Pork Chop',
              'Chicken Breast','Chicken Thigh','Chicken Wing'];

$('#scrollable-dropdown-menu .typeahead').typeahead({
  hint: true,
  highlight: true,
  minLength: 1
},
{
  name: 'ings',
  limit: 3,
  source: substringMatcher(ings)
});

})
  </script>
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
        <form class="form-horizontal" method="POST" action="action.php" enctype="multipart/form-data">
          <legend>Add Recipe</legend>


<div class="form-group">
  <label class="col-md-2 control-label" for="name">Title</label>
  <div class="col-md-10">
  <input id="name" name="name" type="text" max-length="255" class="form-control input-lg" placeholder="Recipe Title" required></div>
</div>

<div class="form-group">
  <label class="col-md-2 control-label" for="desc">Description</label>
  <div class="col-md-10">
    <textarea class="form-control input-lg" id="desc" name="desc" rows="3" placeholder="Enter a brief, enticing description of your recipe here!"></textarea>
  </div>
</div>

<hr>

<div class="form-group">
  <label class="col-md-2 control-label" for="meal_type">Meal Type</label>
  <div class="col-md-4">
    <select id="meal_type" name="meal_type" class="form-control input-lg">
        <?php
        $display_types = "SELECT M.mid, M.type FROM meal_type M ORDER BY M.type ASC";
        if ($all = $mysqli->query($display_types)) {
          while ($row = $all->fetch_row()) {
            echo '<option name="meal_type" value="'.$row[0].'">'.$row[1].'</option>';
          }
        }
        $all->close();

        ?></select>
  </div>
</div>

<hr>

<div class="form-group">
  <label class="col-md-2 control-label">Prep Time</label>
  <div class="col-md-2">
    <div class="input-group">
      <input class="form-control input-lg" name="prep_time" type="number" min="1" max="999" placeholder="0" required>
        <select id="prep_time_units" name="prep_time_units" class="form-control">
          <option value="MINS">MINS</option>
          <option value="HRS">HRS</option>
        </select>
    </div>
    <br>
  </div>

  <label class="col-md-2 control-label">Cook Time</label>
  <div class="col-md-2">
    <div class="input-group">
      <input class="form-control input-lg" name="cooking_time" type="number" min="1" max="999" placeholder="0" required>
        <select id="cooking_time_units" name="cooking_time_units" class="form-control">
          <option value="MINS">MINS</option>
          <option value="HRS">HRS</option>
        </select>
    </div>
    <br>
  </div>

  <label class="col-md-2 control-label" for="servings">Servings</label>
  <div class="col-md-2">
    <select name="servings" class="form-control input-lg">
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
      <option value="7">7</option>
      <option value="8">8</option>
      <option value="9">9</option>
      <option value="10+">10+</option>
    </select>
  </div>
  <br>
</div>

<hr>

<script type="text/javascript">
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({
    html: true,
    container: 'body'
});
});
</script>
<div class="form-group">
  <label class="col-md-2 control-label">Ingredients</label>
  <div class="col-md-4">
    <div id="scrollable-dropdown-menu">
    <input type="text" class="typeahead form-control input-lg" id="search_ingredients" name="search_ingredients" rows="1" placeholder="Add an ingredient...">
  </div><a class="btn btn-xs btn-info" data-toggle="popover" tabindex="0" title="Add Ingredients Help"
  data-content="<b>1.</b> Begin by typing in an ingredient.<br> A good example would be 'Onion'. Then hit ENTER or SEARCH button if on your mobile browser.<br><br><b>2.</b> Please enter quantities with a digit followed by units or simply a digit for whole items. <br><br><center><b>Examples:</b><br>1<br>1 tbsp<br>2 peeled<br>3 diced<br>4 cups<br>5</center><br><b>TIP:</b> It's most likely easier to enter your ingredients first then their quantities.">Help</a></div>
  <div class="col-md-5">
<?php if ($mobile == 'Yes') {
  echo '<br>';
} ?>
    <div id="Ingredient"></div></div>
    <input type="hidden" name="hidden_ingredient">
</div>

<div class="form-group">
  <label class="col-md-2 control-label">Recipe Instructions</label>
  <div class="col-md-9">
    <textarea class="form-control input-lg" name="recipe_instructions" rows="4" placeholder="Paste or write your recipe instructions here."></textarea>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="add"></label>
  <div class="col-md-8">
    <button name="add" type="submit" class="btn btn-success">Add Recipe</button>
    <button name="cancel" onclick="cancel_add();" class="btn btn-danger">Cancel</button>
  </div>
</div>
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

<script>
  $(function() {
    jQuery.scrollSpeed(100, 1000);
  });
</script>
</body>
</html>
