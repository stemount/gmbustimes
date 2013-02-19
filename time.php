<?php
// Get the day of the week we passed from search.php
$day = htmlspecialchars($_REQUEST['day']);
// Get the route the user searched for in search.php
$route = htmlspecialchars($_REQUEST['route']);
// Get the service the user picked in service.php
$service = htmlspecialchars($_REQUEST['service']);
// Get the stop ID we selected in stop.php
$stop = htmlspecialchars($_REQUEST['stop']);
// Get the stop name we selected in stop.php
$stopName = htmlspecialchars($_REQUEST['stopName']);
// Prepare "locking" variables
$starttimeOpen = 0;
$serviceOpen = 0;
// Prepare times array
$timesArray = [];

/**
  * This is where we do things a little bit differently, to make sure that things like Thursdays-only etc. are
  * working, we look up the specific day that it runs on. We do this by looking at a 1/0 value on a "QS" line 
  * (with 1 meaning service runs on this day and 0 it does not). Each day has a specific column to look in and
  * that is what these numbers are. Other columns available are things like Bank Holidays and Schooldays Only 
  * that I have not yet implemented. For further info look at the ATCO-CIF specification                      
  *                                                                                                           
  * http://www.travelinedata.org.uk/CIF/atco-cif-spec.pdf "ATCO File Format for Interchange of Timetable Data"
  * Version 5.0                                                                                               
  *                                                                                                           
  * Note that TfGM use some nonstandard prefixes (starting "Z") which I use to make life a bit easier. If you 
  * wish to adapt this script to work with other agencies' CIF data, you must bear this in mind and change it 
  * accordingly.
*/
// Get a timestamp of the day we selected in search.php
if ($day == strtolower(date('l'))) {
    $date = strtotime(date('Ymd'));
} else {
    $date = strtotime("next " . $day);
}
// Get the column offset for the day selected
if ($day == "monday") {
    $daysOffset = "29";
} else if ($day == "tuesday") {
    $daysOffset = "30";
} else if ($day == "wednesday") {
    $daysOffset = "31";
} else if ($day == "thursday") {
    $daysOffset = "32";
} else if ($day == "friday") {
    $daysOffset = "33";
} else if ($day == "saturday") {
    $daysOffset = "34";
} else if ($day == "sunday") {
    $daysOffset = "35";
}

// For every CIF file that matches the route selected...
foreach(glob("cifdata/*_" . $route . "_.CIF") as $filename){
    // ...Open it
    $lines = file($filename);
    // For each line in it
    foreach($lines as $line_num => $line)
    {
        // If it's a ZS line
        if (substr($line, 0, 2) == "ZS") {
            // If it matches the service we selected in service.php
            if (trim(substr($line, 14, 50)) == $service) {
                // We are allowed to process "QS" lines after this
                $serviceOpen = 1;
                // But we should not process anything else until we find one
                $starttimeOpen = 0;
            } else {
                // We're not allowed to process "QS" lines after this and we should not process anything until the next "ZS"
                $serviceOpen = 0;
                $starttimeOpen = 0;
            }          
        // If we're allowed to process "QS" lines and this is one  
        } else if ($serviceOpen == 1 && substr($line, 0, 2) == "QS") {
            // If this service runs on the day we want (if there's a 1 in rthe column picked before)
            if (substr($line, $daysOffset, 1) == "1") {
                // If is currently used (i.e. it has entered use but is not out of date)
                    if (strtotime(substr($line, 13, 8)) <= $date && (strtotime(substr($line, 21, 8)) >= $date) || substr($line, 21, 8) >= 20990000) {
                    // We're allowed to process "QI", "QO" and "QT" lines
                    $starttimeOpen = 1;
                } else {
                    // We're not allowed to process anything until we find aother "ZS" or "QS" line.
                    $starttimeOpen = 0;
                }
            // If it does not run on the day we want it
            } else {
                // We're not allowed to process anything until we find aother "ZS" or "QS" line. (I think this is safe to remove)
                $starttimeOpen = 0;
            }
        // If we're allowed to process "QI"/"QO" lines (origin/intermediate stops) and this is one
        } else if ($starttimeOpen == 1 && $serviceOpen == 1 && (substr($line, 0, 2) == "QI" || substr($line, 0, 2) == "QO")) {
            // If the line is the stop we picked in stop.php
            if (trim(substr($line, 2, 11)) == $stop) {
                // Put the time on the array
                array_push($timesArray, trim(substr($line, 14, 4)));
                // and we're not allowed to process anything until the next "ZS" or "QS" (Once again, I don't think this in necessary)
                $starttimeOpen = 0;
            }
        // If we're allowed to process "QT" lines and this is one, but NOT if we're searching for a circular (origin is the same as termination) as this results in start and end times on the same page. See #3 for details.
        } else if ($starttimeOpen == 1 && $serviceOpen == 1 && substr($line, 0, 2) == "QT" && !preg_match('#(.*?)(circular|Circular|CIRCULAR)(.*?)#', $service)) {
            // Put the time on the array
            array_push($timesArray, trim(substr($line, 14, 4)));
            // and we're not allowed to process anything until the next "ZS" or "QS" (Believe it or not, probably not required).
            $starttimeOpen = 0;
        }
    }
}

// Check for duplicate times
$stopsArray = array_unique($stopsArray);
// Put times in chronological order
sort($timesArray);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Manchester Bus Times - Timetable</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Searches bus times across Greater Manchester. Times.">
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
        <div class="bar" style="width: 100%;"></div>
      </div>
  </div>
      <div class="span9">
        <p><i class="icon-calendar"></i> 
<?php
$tomorrow = time() + (1 * 24 * 60 * 60);
$dayAfter = time() + (2 * 24 * 60 * 60);
if ($day == strtolower(date('l'))){ // if today is selected
  echo "Today - <a href=\"time.php?stop=" . $stop . "&stopName=" . $stopName . "&route=" . $route . "&day=" . strtolower(date('l', $tomorrow)) . "&service="  . $service . "\">Tomorrow</a> - <a href=\"time.php?stop=" . $stop . "&stopName=" . $stopName . "&route=" . $route . "&day=" . strtolower(date('l', $dayAfter)) . "&service="  . $service . "\">" . date('l', $dayAfter) . "</a>";
}
elseif ($day == strtolower(date('l', $tomorrow))){ // if tomorrow is selected
  echo "<a href=\"time.php?stop=" . $stop . "&stopName=" . $stopName . "&route=" . $route . "&day=" . strtolower(date('l')) . "&service="  . $service . "\">Today</a> - Tomorrow - <a href=\"time.php?stop=" . $stop . "&stopName=" . $stopName . "&route=" . $route . "&day=" . strtolower(date('l', $dayAfter)) . "&service="  . $service . "\">" . date('l', $dayAfter) . "</a>";
}
elseif ($day == strtolower(date('l', $dayAfter))){ // tomorrow+1 is selected
  echo "<a href=\"time.php?stop=" . $stop . "&stopName=" . $stopName . "&route=" . $route . "&day=" . strtolower(date('l')) . "&service="  . $service . "\">Today</a> - <a href=\"time.php?stop=" . $stop . "&stopName=" . $stopName . "&route=" . $route . "&day=" . strtolower(date('l', $tomorrow)) . "&service="  . $service . "\">Tomorrow</a> - " . date('l', $dayAfter);
}
?>
</p>
      </div>
</div>
<div class="row">
  <div class="span12">
    <table class="table">
        <thead>
            <tr>
                <th>Times for
<?php
$tomorrow = time() + (1 * 24 * 60 * 60);
$dayAfter = time() + (2 * 24 * 60 * 60);
echo $service . " at " . $stopName . " (" . $stop . ") on <span class=\"badge badge-info\"><em>" . ucfirst($day);
if ($day == strtolower(date('l'))){
	echo "</em></span> (today)"; 
}
elseif ($day == strtolower(date('l', $tomorrow))){
	echo "</em></span> (tomorrow)";
}
else {
	echo "</em></span>";
}
?>
</th>
            </tr>
        </thead>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="span4">
      <table class="table">
        <tbody>
<?php
$timesCount = count($timesArray);
$timesPerRowFloat = ($timesCount / 3);
$timesPerRow = round($timesPerRowFloat, 0, PHP_ROUND_HALF_UP);
$i = 0;
if (!empty($timesArray)){
	foreach ($timesArray as $time){
    if ($i == $timesPerRow){
      echo "\n</tbody>\n</table>\n</div>\n<div class=\"span4\"><table class=\"table\"><tbody>";
      echo "<tr><td>" . $time . "</td></tr>\n";
      $i = 0;
    }
    else{
      echo "<tr><td>" . $time . "</td></tr>\n";
    }
    $i++;
	}
}
else {
	echo "<tr><td><strong>The stop you selected is not stopped at by the service you selected on this day. Select another service, stop or route, or choose a different day.</strong></td></tr>";
}
?>
            </tbody>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <hr>
  
        <footer>
          <p>&copy; Kieran Mather 2013 - <a href="licence.php">Licence Information</a></p>
        </footer>
    </div>
  </div> <!-- /container -->
</div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js"></script>

  </body>
</html>
