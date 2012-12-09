<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Manchester Bus Times - Christmas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Searches bus times across Greater Manchester. Bank holiday info.">
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
              <li class="active"><a href="holiday.php">Christmas/Bank Holiday Info</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container">

      <h1>Fasten your seatbelt...</h1>
      <h2>...it could get a bit bumpy (and not just because of potholes)</h2>
      <p>During the Christmas period (this year it's the 24th December 2012 to the 2nd January 2013) TfGM's timetable information that is used by the service may not be valid. I've had a look at some of the data and it seems that several timetable changes are made in quick succession near to the end of December but I've not got time to check if they're the real Christmas timetable. So you should</p>
      <h1><a href="http://www.tfgm.com/journey_planning/Pages/Christmas-services.aspx">GO HERE</a> for information 24/12/2012 - 02/01/2013</h1>

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
