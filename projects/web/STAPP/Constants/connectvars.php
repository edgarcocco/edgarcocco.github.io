<?php
if($debug == true){
	define('DB_HOST', 'localhost');
	define('DB_USER', 'Edo');
	define('DB_PASSWORD','legit2013');
	define('DB_NAME','STAPPDB');
}
else
{
	define('DB_HOST', 'localhost');
	define('DB_USER', 'Edo');
	define('DB_PASSWORD','legit2013');
	define('DB_NAME','stappdb');
}
define('TABLE_NAME','stapp_user');
?>
