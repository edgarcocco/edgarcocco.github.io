<?php 

$stripe_publishable_key = "";
$stripe_secret_key = "";
$client_id = "";
if(get_mode() == 'DEBUG'){
	$stripe_publishable_key = "pk_test_ZhMXhjEpiwY5Ikr4qgF9jYlO";
	$stripe_secret_key = "sk_test_U7P2qsCNMCl0DoXNOCcooJ3A";
	$client_id="ca_ERgIiqVGWPE9mIKUNn51ZvoMXk190BVu";
}
if(get_mode() == 'LIVE'){
	$stripe_publishable_key = "pk_live_gt2Z201Ef3bZYkD402letIpg";
	$stripe_secret_key = "sk_live_ytdzmi3BkSIEnXZEGoULW6Pj";
	$client_id="ca_ERgIGgq1mk3GN8Ii1uhDS7aYonqlMLwE";
}
?>

