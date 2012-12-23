<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Manchester Bus Times</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Searches bus times across Greater Manchester">
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
    <link rel="shortcut icon" href="favicon.ico" />
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
        <h3>WARNING!</h3>
        <p>Times on this site are <strong>not</strong> valid during the adjusted Christmas timetables. Go <a href="http://www.tfgm.com/journey_planning/Pages/Christmas-services.aspx">here</a> for timetables. Do not use this site.<p>
      </div>
END;
}
?>
      <h1>Manchester Bus Times</h1>
      <p>Type your route number in the box in the navbar to find times</p>
      <div class="alert alert-info alert-block">
        <h3>Christmas Period</h3>
        <p>Between 24/12/2012 and 02/01/2013 check <a href="http://www.tfgm.com/journey_planning/Pages/Christmas-services.aspx">here</a> for timetables. They will almost certainly not be valid on this site.<p>
      </div>
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
