<?php
$stop = $_REQUEST['stop'];
$day = $_REQUEST['day'];
$route = $_REQUEST['route'];
$filename = glob("cifdata/*_" . $route . "_.CIF");
$file = $filename['0'];
$service = $_REQUEST['service'];

$lines = file($file);
$timesArray = [];
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
				if (substr($line, 13, 8) < date('Ymd') && substr($line, 21, 8) > date('Ymd')) {
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
                <th>Times for <?php echo $service . " at " . $stop . " on " . ucfirst($day); ?></th>
            </tr>
        </thead>
        <tbody>
<?
foreach ($timesArray as $time){
    echo "<tr><td>" . $time . "</td></tr>";
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