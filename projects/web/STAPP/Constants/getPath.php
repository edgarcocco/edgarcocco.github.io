<?php

// This function will return our current project mode
// Whether is DEBUG or LIVE.
function get_mode(){
  $ip = $_SERVER['REMOTE_ADDR'];

  if($ip == "::1")
    return 'DEBUG';
  else
    return 'LIVE';
}

$debug = (get_mode() == 'DEBUG') ? true : false;

if($debug == true){
	$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/'; // Used to include scripts
	$rootUrl = 'http://stappapplocalhost.com/'; // Used to redirect and access main url
}
else {
	$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/';
	$rootUrl = 'https://stappapp.com/';
}
?>
