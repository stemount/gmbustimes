<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Manchester Bus Times - Licence</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Searches bus times across Greater Manchester. Licensing information.">
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
      <div class="hero-unit">
      <h1>Licence Information</h1>
      <p>The boring bit.</p>
    </div>
    <div class="row">
      <div class="span9">
        <h1>Service</h1>
        <p>This section applies if the programme is operated by Kieran Mather as a web service at codedump.eu or a subdomain. If you are not at codedump.eu then you should ask the site owner to remove or change this section to refer to their own site.</p>
        <p>You agree that your access to this service is provided on an 'as-is' basis and that access and/or availability is not guaranteed in any way. In addition, you understand that no warranties, for any reason, are made about the suitability of this service for any reason and that I disclaim any and all liabilities relating to it. Neither I nor any supplier of data to this service make any warranty relating to its accuracy, and if you rely on this service you do so entirely at your own risk.</p>
        <p><strong>tl;dr: it might not work, it might be wrong, it's definitely not my problem (although if you find a problem, contact me and I'll do my best to fix it)</strong></p>
      </div>
    </div>
    <div class="row">
      <div class="span9">
        <h1>Programme</h1>
        <p>The programme code that powers the service is made available to you at <a href="https://github.com/kieranmather/gmbustimes">https://github.com/kieranmather/gmbustimes</a>.
        <h3>Manchester Bus Times</h3>
        <p>This program is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version. This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details. You should have received a copy of the GNU Affero General Public License along with this program. If not, see http://www.gnu.org/licenses/.</p>
        <p><strong>tl;dr: do what you want with it, but make sure you share!</strong></p>
        <h3>Bootstrap</h3>
        <p>This programme contains software ("Bootstrap") by Twitter, Inc., the licence statement of which is below:</p>
        <blockquote>
        <p>Copyright 2011 Twitter, Inc.</p>
        <p>http://www.apache.org/licenses/LICENSE-2.0</p>
        <p>Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.</p>
        </blockquote>
        <p>The full licence is available at the above URL. Files covered by this licence have a header identifying this.</p>
        <p><strong>tl;dr: Twitter are awesome and made a cool web framework</strong></p>
        <h3>Theme</h3>
        <p>This programme contains software ("Cosmo") by Thomas Park, the licence statement of which is below:</p>
        <blockquote>
          <p>Copyright 2012 Thomas Park</p>
          <p>Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at</p>
          <p>http://www.apache.org/licenses/LICENSE-2.0</p>
          <p>Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.</p>
        </blockquote>
        <p>This applies to /vendor/css/cosmo.min.css</p>
        <p><strong>tl;dr: Thomas Park made a good-looking theme and I was too lazy to make my own. He's got a few so <a href="http://bootswatch.com/">go look at them</a></strong></p>
      </div>
    </div>
    <div class="row">
      <div class="span9">
        <h1>Data</h1>
        <p>Uses data that is &copy; 2013 Transport for Greater Manchester</p>
        <p>Contains Ordnance Survey data &copy; Crown copyright and database rights 2013</p>
        <p><a href="http://store.datagm.org.uk/sets/TfGM/FreeToUseData_SubLicence_v1.pdf">Full Licence</a></p>
        <p>You can get a copy of the data to be used with this programme at <a href="http://store.datagm.org.uk/sets/TfGM/GMPTE_CIF.zip">http://store.datagm.org.uk/sets/TfGM/GMPTE_CIF.zip</a>.
        <p><strong>tl;dr: do what you want, but give 'er Maj some credit</strong></p>
        <p><small>And it says I've got to require you to follow the licence, so... do it, kay?</small></p>
      </div>
    </div>
    <div class="row">
      <div class="span9">
        <h1>Icons</h1>
        <p>Uses <a href="http://glyphicons.com/">Glyphicons</a> Halflings</p>
      </div>
    </div>

      <hr>

      <footer>
        <p>&copy; Kieran Mather 2013 - <a href="licence.php">Licence Information</a></p>
      </footer>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.2.1/bootstrap.min.js"></script>

  </body>
</html>
