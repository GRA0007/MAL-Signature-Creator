<?php
if (empty($_POST)) {
	header("Location: ../");
	die('Alright, something definitely went wrong there.');
}

if ($_POST['username'] == 'GRA0007') {
	if ($_POST['password'] == 'sparkle') {
		$login = [
			"username" => $_POST['username'],
			"password" => $_POST['password']
		];
		setcookie("MALSIG_LOGIN", serialize($login), time()+60*60*24*30, '/');
		header("Location: ../editor/");
		die();
	} else {
		setcookie("MALSIG_LOGIN", "nothing", 1, '/');
		header("Location: ../");
		die('That was not right at all.');
	}
} else {
	setcookie("MALSIG_LOGIN", "nothing", 1, '/');
	header("Location: ../");
	die('That was not right at all.');
}