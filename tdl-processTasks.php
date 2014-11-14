<?php
	/*
	 *  File for processing creation and modification of tasko
	 */
session_start();
require_once('tdl-config.php');

$mysqli = new mysqli(TDL_DBURI, TDL_DBUSER, TDL_DBPASS, TDL_DBNAME);
	
	/* check connection */
	if ($mysqli->connect_errno) {
    	printf("Connect failed: %s\n", $mysqli->connect_error);
    	exit();
	}

if (isset($_GET["add"]) {
	$toProcess = explode(" ",$_POST["task"]);
	$project = "";
	$labels = [];
	$taskName = "";
	for ($i = 0; $i < count($toProcess); $i++) {
		if (strpos($toProcess[$i], "#") == 0) {
			// labels			
			$labels[] = substr($toProcess[$i], 1);
		} else if ((strpos($toProcess[$i], "@") == 0) && (empty($project))) {
			// project
			$project = substr($toProcess[$i], 1);
		} else {
			$taskName .= " ".$toProcess[$i];
		}
	}
	$preparedLabels = implode(",", $labels);
	
}
?>