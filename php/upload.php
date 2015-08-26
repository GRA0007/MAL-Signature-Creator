<?php
$output_dir = "D:/Apache/htdocs/experiments/MAL_sig/images/";
if (isset($_FILES["file"])) {
	//Filter the file types , if you want.
	if ($_FILES["file"]["error"] > 0) {
		echo "Error: " . $_FILES["file"]["error"];
	} else {
		move_uploaded_file($_FILES["file"]["tmp_name"],$output_dir. $_FILES["file"]["name"]);
		echo "Uploaded File :".$_FILES["file"]["name"];
	}
}