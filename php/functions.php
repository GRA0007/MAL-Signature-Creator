<?php
if (empty($_GET)) { die('There was no data specified.'); }
if (empty($_COOKIE['MALSIG_LOGIN'])) { die('You are unauthorized.'); }

$output_dir = "D:/Apache/htdocs/experiments/MAL_sig/images/";
$formats_dir = "D:/Apache/htdocs/experiments/MAL_sig/formats.txt";

$recoverFormats = file_GET_contents($formats_dir); //Get the scrambled array from file
$formats = unserialize(base64_decode($recoverFormats)); //Recover the formats array

/*
if ($_GET['mode'] == 'save') {
	//Save an existing image
	file_put_contents('../raw/files/' . $_POST['id'] . '.' . $formats[$_POST['id']]['format'], $_POST['data']); //Save the file
	echo 'saved'; //Tell the other file
*/
if ($_GET['mode'] == 'load') {
	//Load a file
	$send_data = array();
	$j = 0;
	for ($i = 0; $i < count($formats); $i++) {
		if ($formats[$i] != 'deleted') {
			if ($formats[$i]['owner'] == unserialize($_COOKIE['MALSIG_LOGIN'])['username']) {
				$send_data[$j] = $formats[$i];
				$send_data[$j]['id'] = $i;
				$j++;
			}
		}
	}
	//$data = file_GET_contents('../raw/files/' . $_POST['id'] . '.' . $formats[$_POST['id']]['format']); //Grab the file contents
	echo JSON_ENCODE($send_data); //Feed them back to the other file
} else if ($_GET['mode'] == 'new') {
	//Save a new image
	$number = count($formats); //New id
	if (isset($_FILES["file"])) {
		//Filter the file types , if you want.
		if ($_FILES["file"]["error"] > 0) {
			die("Error: " . $_FILES["file"]["error"]);
		} else {
			move_uploaded_file($_FILES["file"]["tmp_name"],$output_dir. $number . '.' . array_pop(explode('.', $_FILES["file"]["name"])));
			//echo "Uploaded File :".$_FILES["file"]["name"];
			$formats[$number] = array( //Insert the details
				'title' => $_FILES["file"]["name"], //Name of image
				'owner' => unserialize($_COOKIE['MALSIG_LOGIN'])['username'] //The owner's username
			);
			$serializeFormats = base64_encode(serialize($formats)); //Scramble the formats array
			file_put_contents($formats_dir, $serializeFormats); //Save the scrambled array
			echo $number; //Spread the good news
		}
	}
/*
} else if ($_GET['mode'] == 'edit') {
	//Edit a file's details
	$oldFormat = $formats[$_POST['id']]['format']; //Get the file's previous format
	if ($oldFormat != $_POST['format']) {
		$oldData = file_GET_contents('../raw/files/' . $_POST['id'] . '.' . $oldFormat); //Get the old file's data
		unlink('../raw/files/' . $_POST['id'] . '.' . $oldFormat); //Delete the old file
		file_put_contents('../raw/files/' . $_POST['id'] . '.' . $_POST['format'], $oldData); //Make the new file
	}
	$formats[$_POST['id']] = array( //Replace the details
		format => $_POST['format'], //txt or json
		title => $_POST['title'], //Title of doc
		owner => unserialize($_COOKIE['login'])['username'] //The owner's username
	);
	$serializeFormats = base64_encode(serialize($formats)); //Scramble the formats array
	file_put_contents('../raw/formats.txt', $serializeFormats); //Save the scrambled array
	echo 'edited'; //Spread the good news
*/
} else if ($_GET['mode'] == 'delete') {
	//Delete the image (permanent)
	unlink($output_dir . $_GET['id'] . '.' . array_pop(explode('.', $formats[$_GET['id']]['title']))); //Delete the file
	$formats[$_GET['id']] = 'deleted'; //Replace the details
	$serializeFormats = base64_encode(serialize($formats)); //Scramble the formats array
	file_put_contents($formats_dir, $serializeFormats); //Save the scrambled array
	echo 'deleted'; //Spread the good (bad?) news
} else if (($_GET['mode'] == 'init') && ($_GET['secret'] == '53Kr7Vxa4LPkshnTevQM')) {
	//Reset everything (LAST RESORT)
	$data = array(
		array(
			'title' => 'Caramel Slice.jpg',
			'owner' => 'GRA0007'
		), array(
			'title' => 'Coffee in a cube.png',
			'owner' => 'GRA0007'
		)
	);
	$serializeData = base64_encode(serialize($data));
	file_put_contents($formats_dir, $serializeData);
} else {
	//Wrong mode
	die('Incorrect mode specified');
}