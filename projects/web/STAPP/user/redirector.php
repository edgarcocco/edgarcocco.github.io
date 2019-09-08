<?php


if(isset($_GET['from']))
{
	$from = $_GET['from'];
	if($from == 'hubeditor')
	{
		header('Location: hubeditor.php');
	}
}
else
{
	header('Location: ../index.php');
}

?>
