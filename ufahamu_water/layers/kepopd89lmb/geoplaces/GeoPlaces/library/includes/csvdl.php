<?php

$csvfilepath = site_url() ."/wp-content/themes/".get_option( 'template' )."/post_sample.csv";

header('Content-Description: File Transfer');

header('Content-Type: application/octet-stream');

header("Content-Type: image/png");

header("Content-type: application/force-download");

header('Content-Disposition: inline; filename="post_sample.csv"');

header('Content-Transfer-Encoding: binary');

readfile($csvfilepath);

exit;

?>