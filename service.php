<?php
$day = $_REQUEST['day']; // day of the week in lowercase
$route = $_REQUEST['route']; // route number in uppercase
$servicesArray = [];

$dayOpen = 0;
if ($day == "monday" || $day == "tuesday" || $day == "wednesday" || $day == "thursday" || $day == "friday"){
	$dayRange = "Mondays to Fridays";
}
elseif ($day == "saturday"){
	$dayRange = "Saturdays";
}
elseif ($day == "sunday"){
	$dayRange = "Sundays";
}
foreach (glob("cifdata/*_" . $route . "_.CIF") as $filename){ // for every cif file that matches the route number (done because some routes have 2 files, like the 192 having GM__192_.CIF *and* GMN_192_.CIF for its night service)
	$lines = file($filename); // open the file
	foreach($lines as $line_num => $line) // for every line in the file
	{
		if (substr($line, 0, 2) == "ZD"){ // date declaration (my term)
			if (trim(substr($line, 18, 64)) == $dayRange && substr($line, 2, 8) < date('Ymd') && substr($line, 21, 8) > date('Ymd')){
				$dayOpen = 1; // parse following lines
			}
			else {
				$dayOpen = 0;
			}
		}
		elseif ($dayOpen == 1 && substr($line, 0, 2) == "ZS"){ // if the previous date declaration match and it's a service declaration
			array_push($servicesArray, (trim(substr($line, 14, 50)))); // put the service name into $servicesArray
		}	

	}
}
$servicesArray = array_unique($servicesArray); // get rid of duplicates

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Manchester Bus Times - Services</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Searches bus times across Greater Manchester. Service Selection.">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="vendor/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="vendor/css/bootstrap-responsive.css" rel="stylesheet">

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
                <th class="pull-right"><i class="icon-calendar"></i> 
<?php
$tomorrow = time() + (1 * 24 * 60 * 60);
$dayAfter = time() + (2 * 24 * 60 * 60);
if ($day == strtolower(date('l'))){ // if today is selected
	echo "Today - <a href=\"service.php?route=" . $route . "&day=" . strtolower(date('l', $tomorrow)) . "\">Tomorrow</a> - <a href=\"service.php?route=" . $route . "&day=" . strtolower(date('l', $dayAfter)) . "\">" . date('l', $dayAfter) . "</a>";
}
elseif ($day == strtolower(date('l', $tomorrow))){ // if tomorrow is selected
	echo "<a href=\"service.php?route=" . $route . "&day=" . strtolower(date('l')) . "\">Today</a> - Tomorrow - <a href=\"service.php?route=" . $route . "&day=" . strtolower(date('l', $dayAfter)) . "\">" . date('l', $dayAfter) . "</a>";
}
elseif ($day == strtolower(date('l', $dayAfter))){ // tomorrow+1 is selected
	echo "<a href=\"service.php?route=" . $route . "&day=" . strtolower(date('l')) . "\">Today</a> - <a href=\"service.php?route=" . $route . "&day=" . strtolower(date('l', $tomorrow)) . "\">Tomorrow</a> - " . date('l', $dayAfter);
}
?>
</th>
            </tr>
        </thead>
        <tbody>
<?
if (!empty($servicesArray)){ // if the route entered has services running on $day...
	foreach ($servicesArray as $service){ // for each service
		echo "<tr><td><a href=\"stop.php?route=" . $route . "&day=" . $day . "&service=" . $service . "\">" . $service . "</a></td></tr>"; // add a <tr> with the name and a link to the next page
	}
}
else { // if there's no running services today... 
	echo "<tr><td><strong>This route does not appear to have any services running on the day you selected.</strong></td></tr>"; // send a <tr> with an error
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
