<?php
$day = $_REQUEST['day'];
$route = $_REQUEST['route'];
$servicesArray = [];

$dayOpen = 0;
if ($day == "monday" || $day == "tueday" || $day == "wednesday" || $day == "thursday" || $day == "friday"){
	$dayRange = "Mondays to Fridays";
}
elseif ($day == "saturday"){
	$dayRange = "Saturdays";
}
elseif ($day == "sunday"){
	$dayRange = "Sundays";
}
foreach (glob("cifdata/*_" . $route . "_.CIF") as $filename){
	$lines = file($filename);
	foreach($lines as $line_num => $line)
	{
		if ($dayOpen == 1){
		}
		if (substr($line, 0, 2) == "ZD"){ 
			if (trim(substr($line, 18, 64)) == $dayRange && substr($line, 2, 8) < date('Ymd') && substr($line, 21, 8) > date('Ymd')){
				$dayOpen = 1;
			}
			else {
				$dayOpen = 0;
			}
		}
		elseif ($dayOpen == 1 && substr($line, 0, 2) == "ZS"){ 
			array_push($servicesArray, (trim(substr($line, 14, 50))));
		}	

	}
}
$servicesArray = array_unique($servicesArray);

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Manchester Bus Times</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Searches bus times across Greater Manchester. Route not found.">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->

  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="index.php">Manchester Bus Times</a>
          <div class="collapse nav-collapse">
            <form method="post" action="search.php" class="navbar-form pull-right">
                <input type="text" class="span2" name="s">
                <button type="submit" class="btn">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="container">

    <table class="table">
        <thead>
            <tr>
                <th>Services for route <?php echo $route; ?></th>
            </tr>
        </thead>
        <tbody>
<?
foreach ($servicesArray as $service){
    echo "<tr><td><a href=\"stop.php?route=" . $route . "&day=" . $day . "&servce=" . $service . "&service=" . $service . "\">" . $service . "</a></td></tr>";
}
?>
        </tbody>
    </table>
      <hr>

      <footer>
        <p>&copy; Kieran Mather 2012 - <a href="licence.php">Licence Information</a></p>
      </footer>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.2.1/bootstrap.min.js"></script>

  </body>
</html>
