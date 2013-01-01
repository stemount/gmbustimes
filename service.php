<?php
// Get the day of the week that was passed from search.php, hopefully in lowercase, htmlspecialchars stops any XSS business going on
$day = htmlspecialchars($_REQUEST['day']);
// Get the route the user searched for that was passed from search.php, should be in uppercase
$route = htmlspecialchars($_REQUEST['route']);

// Prepare array that will contain services
$servicesArray = [];
// Prepare "locking" variable, this is set to 1 when we reach a "ZD" line that matches the day range set below, making sure that services that don't run on the day selected aren't displayed
$dayOpen = 0;

// "ZD" lines tell us what days the service runs on and have one of the following three values. Pick the correct one for the day selected.
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
    // For each line in it...
    foreach($lines as $line_num => $line) // for every line in the file
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
            // Push it onto the array
            array_push($servicesArray, (trim(substr($line, 14, 50))));
        }
        
    }
}
// Combine any duplicate services (which there are lots of in the CIF files for no apparent reason)
$servicesArray = array_unique($servicesArray);

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
    <link href="vendor/css/cosmo.min.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="vendor/css/bootstrap-responsive.min.css" rel="stylesheet">
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
        <div class="bar" style="width: 33%;"></div>
      </div>
  </div>
      <div class="span9">
        <p><i class="icon-calendar"></i> 
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
</p>
      </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Services for route <?php echo $route; ?></th>
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
