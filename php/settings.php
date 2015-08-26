<?php
if (empty($_GET)) { die('There was no data specified.'); }
if (empty($_COOKIE['MALSIG_LOGIN'])) { die('You are unauthorized.'); }

$xml = simplexml_load_file("D:/Apache/htdocs/experiments/MAL_sig/signatures.xml") or die("Error: Cannot create object");
$currentSig;

foreach ($xml->MALsig as $MALsig) {
	if ($MALsig['owner'] == unserialize($_COOKIE['MALSIG_LOGIN'])['username']) {
		$currentSig = $MALsig;
	}
}

if ($_GET['mode'] == 'general') {
}