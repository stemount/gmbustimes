<?php
// Get the day of the week we passed from search.php
$day = htmlspecialchars($_REQUEST['day']);
// Get the route the user searched for in search.php
$route = htmlspecialchars($_REQUEST['route']);
// Get the service the user picked in service.php
$service = htmlspecialchars($_REQUEST['service']);
// Prepare array to store stop codes (e.g. 1800STBS001)
$stopsArray = [];
// Prepare "locking" variable for days
$dayOpen = 0;

// Find the day range, you know the drill by now
if ($day == "monday" || $day == "tuesday" || $day == "wednesday" || $day == "thursday" || $day == "friday") {
    $dayRange = "Mondays to Fridays";
} else if ($day == "saturday") {
    $dayRange = "Saturdays";
} else if ($day == "sunday") {
    $dayRange = "Sundays";
}

// For every CIF file that matches the route selected...
foreach(glob("cifdata/*_" . $route . "_.CIF") as $filename){
    // ...Open it
    $lines = file($filename);
    // For each line in it
    foreach($lines as $line_num => $line)
    {
        // If it is a "ZD" line (which tells us the day(s) it runs on)
        if (substr($line, 0, 2) == "ZD") {
            // If it matches the day range ($dayRange) we picked before AND it is currently used (i.e. it has entered use but is not out of date)
            if (trim(substr($line, 18, 64)) == $dayRange && substr($line, 2, 8) < date('Ymd') && substr($line, 21, 8) > date('Ymd')) {
                // We're allowed to process "ZS" lines after this
                $dayOpen = 1;
            } else {
                // We're not allowed to process "ZS" lines after this
                $dayOpen = 0;
            }
        // If it's a "ZS" line (which contains the name of the service)
        } else if ($dayOpen == 1 && substr($line, 0, 2) == "ZS") {
            // If it matches the service we picked before
            if (trim(substr($line, 14, 50)) == $service) {
                // We're allowed to process "ZA" lines after this
                $serviceOpen = 1;
            } else {
                // We're not allowed to process "ZA" lines after this
                $serviceOpen = 0;
            }
        // If we're allowed to process ZA lines and it is one
        } else if ($dayOpen == 1 && $serviceOpen == 1 && substr($line, 0, 2) == "ZA") {
            // If it's a TfGM stop (starting with 180) we perform extra logic to get correct stops and stations
            if (substr($line, 3, 3) == "180"){
                // If the last 3 characters of a stop ID are digits (which prevents "phantom" stops that are listed but are stopped at by no journeys)
                if (ctype_digit(substr($line, 11, 3))) {
                    // If it's a station (identified by 4 contiguous letters in the middle instead of two or three)...
                    if (preg_match("/\D\D\D\D/", substr($line, 3, 11))) {
                        // ... If it's the correct station and not a phantom one (note that all "real" stations in GM end in 001 but if you're adapting this script for other CIF you may have to find another way of doing this)
                        if (preg_match("/001/", substr($line, 11, 3))) {
                            // Put it on the array
                            $stopsArray[substr($line, 3, 11)] = trim(substr($line, 15, 48));
                        }
                    } else {
                        // It's just a normal stop and we put it on the array
                        $stopsArray[substr($line, 3, 11)] = trim(substr($line, 15, 48));
                    }
                }
            } else {
                // If it's not a TfGM stop then just put it on the array as we don't know how their stop numbering system works
                $stopsArray[substr($line, 3, 11)] = trim(substr($line, 15, 48));
            }
        }
    }
}
// Combine duplicated stops
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
    <link href="//netdna.bootstrapcdn.com/bootswatch/2.3.0/cosmo/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="shortcut icon" href="favicon.ico">
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
          <a class="brand" href="index.php"><img src="vendor/img/icon-bus.png" style="height: 20px; padding-right: 5px;">Manchester Bus Times</a>
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
<?php
$holidayStart = strtotime("2013-03-27");
$holidayEnd = strtotime("2013-04-02");
$currentDate = time();
if ($currentDate > $holidayStart && $currentDate < $holidayEnd){
echo <<<END
      <div class="alert alert-warning alert-block">
        <h3>WARNING!</h3>
        <p>Times on this site may <strong>not</strong> be valid on Good Friday (29th March) and Easter Sunday (1st April). Check with your operator for details.<p>
      </div>
END;
}
?>
<div class="row">
  <div class="span4">
      <div class="progress">
        <div class="bar" style="width: 66%;"></div>
      </div>
  </div>
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
        <p>&copy; Kieran Mather 2013 - <a href="licence.php">Licence Information</a><!-- SPONSOR TAG --></p>
      </footer>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js"></script>

  </body>
</html>