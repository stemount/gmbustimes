<?php
$day = $_REQUEST['day'];
$route = $_REQUEST['route'];
$filename = glob("cifdata/*_" . $route . "_.CIF");
$file = $filename['0'];
$service = $_REQUEST['service'];
$stopsArray = [];
$stopNamesArray = [];
$lines = file($file);
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
foreach (glob("cifdata/*_" . $route . "_.CIF") as $filename){
	$lines = file($filename);
	foreach($lines as $line_num => $line)
	{
		if (substr($line, 0, 2) == "ZD"){ 
			if (trim(substr($line, 18, 64)) == $dayRange && substr($line, 2, 8) < date('Ymd') && substr($line, 21, 8) > date('Ymd')){ // Check that this service is the one we want
				$dayOpen = 1;
			}
			else {
				$dayOpen = 0;
			}
		}
		elseif ($dayOpen == 1 && substr($line, 0, 2) == "ZS"){ 
			if (trim(substr($line, 14, 50)) == $service){
				$serviceOpen = 1;
			}
			else{
				$serviceOpen = 0;
			}
		}
		elseif ($dayOpen == 1 && $serviceOpen == 1 && substr($line, 0, 2) == "ZA"){
			/* array_push($stopsArray, substr($line, 3, 11));
			array_push($stopNamesArray, trim(substr($line, 16, 48))); */
	        if (ctype_digit(substr($line, 11, 3))){
            if (preg_match("/\D\D\D\D/", substr($line, 3, 11))){ // If it's a station (identified by 4 contiguous letters in the middle instead of two or three)...
              if (preg_match("/001/", substr($line, 11, 3))){ // ... Check if it's the _station_ (which is in the timetable) and not a _stand_ (which is in the TT but not stopped at)
				        $stopsArray[substr($line, 3, 11)] = trim(substr($line, 15, 48));
              }
            }
          else { // If it's not a station then we can add it to the array
            $stopsArray[substr($line, 3, 11)] = trim(substr($line, 15, 48));
          }
          }
		}
	}
}
$stopsArray = array_unique($stopsArray);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Manchester Bus Times - Stops</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Searches bus times across Greater Manchester. Stop Selection.">
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
            <ul class="nav pull-right">
              <li><a href="holiday.php">Christmas/Bank Holiday Info</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
<div class="row">
      <div class="span9">
        <p><i class="icon-calendar"></i> 
<?php
$tomorrow = time() + (1 * 24 * 60 * 60);
$dayAfter = time() + (2 * 24 * 60 * 60);
if ($day == strtolower(date('l'))){ // if today is selected
  echo "Today - <a href=\"stop.php?route=" . $route . "&day=" . strtolower(date('l', $tomorrow)) . "&service="  . $service . "\">Tomorrow</a> - <a href=\"stop.php?route=" . $route . "&day=" . strtolower(date('l', $dayAfter)) . "&service="  . $service . "\">" . date('l', $dayAfter) . "</a>";
}
elseif ($day == strtolower(date('l', $tomorrow))){ // if tomorrow is selected
  echo "<a href=\"stop.php?route=" . $route . "&day=" . strtolower(date('l')) . "&service="  . $service . "\">Today</a> - Tomorrow - <a href=\"stop.php?route=" . $route . "&day=" . strtolower(date('l', $dayAfter)) . "&service="  . $service . "\">" . date('l', $dayAfter) . "</a>";
}
elseif ($day == strtolower(date('l', $dayAfter))){ // tomorrow+1 is selected
  echo "<a href=\"stop.php?route=" . $route . "&day=" . strtolower(date('l')) . "&service="  . $service . "\">Today</a> - <a href=\"stop.php?route=" . $route . "&day=" . strtolower(date('l', $tomorrow)) . "&service="  . $service . "\">Tomorrow</a> - " . date('l', $dayAfter);
}
?>
</p>
      </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Stops for <?php echo $service; ?></th>
            </tr>
        </thead>
        <tbody>
<?php
if (!empty($stopsArray)){
	foreach ($stopsArray as $stopID => $stopName) {
		echo "<tr><td><a href=\"time.php?stop=" . $stopID . "&stopName=" . $stopName .  "&route=" . $route . "&day=" . $day . "&service=" . $service . "\">" . $stopName . "</a></td></tr>";
	}
}
else {
	echo "<tr><td><strong>This service you selected does not appear to run on the date you selected. Either select another date, go back to select another service or enter a new route.</strong></td></tr>";
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