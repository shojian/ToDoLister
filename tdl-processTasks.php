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

if (isset($_GET["add"])) {
	$toProcess = explode(" ",$_POST["task"]);
	$project = "";
	$labels = [];
	$taskName = "";
	for ($i = 0; $i < count($toProcess); $i++) {
		if (strpos($toProcess[$i], "#") == 0) {
			// labels			
			if (!is_numeric(substr($toProcess[$i], 1))) // allowing to write "I'm #1"
				$labels[] = substr($toProcess[$i], 1);
		} else if ((strpos($toProcess[$i], "@") == 0) && (empty($project))) {
			// project
			$project = substr($toProcess[$i], 1);
		} else {
			$taskName .= " ".$toProcess[$i];
		}
	}
	$preparedLabels = implode(",", $labels);	
	/*
	 * 	Rewrite following section!!!
	 *  dd. mm. yyyy
	 */
	$dlPrep = strptime($_POST["deadline"], '%e. %m. %Y');
	$deadline = mktime(0, 0, 0, $dlPrep['tm_mon']+1, $dlPrep['tm_mday'], $dlPrep['tm_year']+1900);
	// Needs to parse if it is repeatable deadline or once in a lifetime
	if ($stmt = $mysqli->prepare("INSERT INTO TASKS (taskname, project, labels, deadline) VALUES (?, ?, ?, ?)")) {
		$stmt->bind_param("sssi", $taskName, $project, $labels, $deadline);
		$stmt->execute();
		$stmt->close();
	}
	
}

if (isset($_GET["done"]) && isset($_POST["toBeRemoved"])) {	 
	 // Get info about task from TASKS table
	 if ($stmt = $mysqli->prepare("SELECT id, taskname, project, labels FROM TASKS WHERE id=?;") {
	 	$stmt->bind_param("i", $_POST["toBeRemoved"]);
	 	$stmt->execute();
		$stmt->bind_result($id, $name, $project, $labels);
	 // Add info to Done table
	 	 
	 // If non-renewable remove task from TASKS table
	 
	 // otherwise renew task in TASKS table
	 } else {
	 	// Throw some error
	 	
	 }
	 
}

if (isset($_GET["remove"]) && isset($_POST["toBeRemoved"])) {
	/*
	 *  id cannot be post. It can in theory but just to be on safe side POST is ok, DELETE would be better
	 */
	 if ($stmt = $mysqli->prepare("DELETE FROM TASKS WHERE id=? LIMIT 1")) {
	 	$stmt->bind_param("i", $_POST["toBeRemoved"]);
	 	$stmt->execute();
	 	$stmt->close();
	 }
}
?>