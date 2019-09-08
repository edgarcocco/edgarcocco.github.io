<?php
unset($_SERVER['PHP_AUTH_USER']);
unset($_SERVER['PHP_AUTH_PW']);

$_SERVER['PHP_AUTH_USER'] = null;
$_SERVER['PHP_AUTH_PW'] = null;

echo $_SERVER['PHP_AUTH_USER'];
echo $_SERVER['PHP_AUTH_PW'];
?>
