<?php
$route = strtoupper(htmlspecialchars($_REQUEST['s'])); // file names are all uppercase so that's what we want here
$found = glob("cifdata/*_" . $route . "_.CIF"); // search cifdata for a file with the route number
$day = strtolower(date('l')); // get the current day of the week and make it lowercase, this will eventually be taken from postdata so the user can select a day
if (!empty($found)){ // if we've found the route
	header('Location: service.php?route=' . $route . "&day=" . $day); // send them here
}
// otherwise display the error page
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Manchester Bus Times - Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Searches bus times across Greater Manchester. Route not found.">
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
      <h1>Route not found!</h1>
      <p>Try typing it in again, making sure there's no spaces in the box. It's also possible that the route was recently created or withdrawn, check with TfGM.</p>

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
