<?php
$stop = $_REQUEST['stop'];
$day = $_REQUEST['day'];
$route = $_REQUEST['route'];
$stopName = $_REQUEST['stopName'];
$filename = glob("cifdata/*_" . $route . "_.CIF");
$file = $filename['0'];
$service = $_REQUEST['service'];
$starttimeOpen = 0;
$serviceOpen = 0;
$lines = file($file);
$timesArray = [];
if ($day == strtolower(date('l'))){
  $date = strtotime(date('Ymd'));
}
else {
  $date = strtotime("next " . $day);
}
if ($day == "monday")
	$daysOffset = "29";
elseif ($day == "tuesday")
	$daysOffset = "30";
elseif ($day == "wednesday")
	$daysOffset = "31";
elseif ($day == "thursday")
	$daysOffset = "32";
elseif ($day == "friday")
	$daysOffset = "33";
elseif ($day == "saturday")
	$daysOffset = "34";
elseif ($day == "sunday")
	$daysOffset = "35";
foreach (glob("cifdata/*_" . $route . "_.CIF") as $filename){
	$lines = file($filename);
	foreach($lines as $line_num => $line)
	{
		if (substr($line, 0, 2) == "ZS"){ 
			if (trim(substr($line, 14, 50)) == $service){ // Check that this service is the one we want
				/*$service = trim(substr($line, 14, 50));*/
				$serviceOpen = 1;
				$starttimeOpen = 0;
			}
			else {
				$serviceOpen = 0;
				$starttimeOpen = 0;
			}
			$starttimeOpen = 0;	

		}
		elseif ($serviceOpen == 1 && substr($line, 0, 2) == "QS"){
			if (substr($line, $daysOffset, 1) == "1"){
				if (strtotime(substr($line, 13, 8)) <= $date && strtotime(substr($line, 21, 8)) >= $date) {
					$starttimeOpen = 1;
				}
				else {
					$starttimeOpen = 0;
				}
			}
			else {
				$starttimeOpen = 0;
			}
		}
		elseif ($starttimeOpen == 1 && $serviceOpen == 1 && (substr($line, 0, 2) == "QI" || substr($line, 0, 2) == "QO")) {
			if (trim(substr($line, 2, 11)) == $stop){ // Check that this stop is the one we want to get times for
				array_push($timesArray, trim(substr($line, 14, 4)));	
				$starttimeOpen = 0;
      }
    }
    elseif ($starttimeOpen == 1 && $serviceOpen == 1 && substr($line, 0, 2) == "QT" && !preg_match('#(.*?)(circular|Circular|CIRCULAR)(.*?)#', $service)){
      array_push($timesArray, trim(substr($line, 14, 4)));  
      $starttimeOpen = 0;
    }
	}
}

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
    <link href="vendor/css/cosmo.min.css" rel="stylesheet">
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
                <ul class="nav">
    <li><a href="holiday.php">Christmas/Bank Holiday Info</a></li>
    </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
<?php
// Holday services warning. You can remove this if you wish.
$holidayStart = strtotime("2012-12-24"); // ISO date format FTW
$holidayEnd = strtotime("2013-01-02");
$currentDate = time();
if ($currentDate > $holidayStart && $currentDate < $holidayEnd){
echo <<<END
      <div class="alert alert-error alert-block">
        <h2>WARNING!</h2>
        <h3><strong>THE TIMES ON THIS PAGE ARE PROBABLY NOT THE ONES YOU WANT!</strong></h3>
        <p>Times on this site are <strong>not</strong> valid during the adjusted Christmas timetables. Go <a href="http://www.tfgm.com/journey_planning/Pages/Christmas-services.aspx">here</a> for timetables. Do not use this site.<p>
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
        <tbody>
<?
if (!empty($timesArray)){
	foreach ($timesArray as $time){
	    echo "<tr><td>" . $time . "</td></tr>";
	}
}
else {
	echo "<tr><td><strong>The stop you selected is not stopped at by the service you selected on this day. Select another service, stop or route, or choose a different day.</strong></td></tr>";
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