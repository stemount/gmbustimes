# Greater Manchester Bus Times
## Notice
You must have PHP>=5.4 for this to work. An untested workaround is to replace
```php
$exampleArray = [];
```
with
```php
$exampleArray = array();
```
## Info
This script parses TfGM's ATCO-CIF data (placed in ./cifdata) to provide a nice-looking timetable service. Confirmed working with latest CIF dated 23/01/13
## Usage
Extract [TfGM's CIF data](http://store.datagm.org.uk/sets/TfGM/GMPTE_CIF.zip) into cifdata. The scripts should be working from this point and you can go from there.
## Licence
GNU AGPL, Further info in COPYING
### Bus icon in title
Based on [this image](http://commons.wikimedia.org/wiki/File:BER-Bus.svg) and is public domain.
### Other Software
Contains Bootstrap from Twitter which is licenced under the Apache Licence.
Contains Cosmo from Bootswatch which is licenced under the Apache Licence.
Contains Glyphicons which (in this case) are part of Bootstrap but deserve a separate mention.
### Data
This repository contains no data for the scripts to work with, you must obtain this yourself (see Usage). Copyright notices related to TfGM's CIF data are included in licence.php but these obviously only apply if you use their data.
## Sponsor Tags
These are just for my use. Bytemark sponsor my server in return for me placing the badges on pages. When I pull commits to my web server, I use a script that replaces the html comments with the badge. Nothing will show on your site (except in HTML source) and you can safely delete them.
## Working Implementation
[Here](http://www.codedump.eu/buses/)
