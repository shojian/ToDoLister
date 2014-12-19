<?php
	/*
	 *  File for processing creation and modification of tasko
	 */
session_start();
require_once('tdl-config.php');
require_once('tdl-deadline.php');

$mysqli = new mysqli(TDL_DBURI, TDL_DBUSER, TDL_DBPASS, TDL_DBNAME);
	
	/* check connection */
	if ($mysqli->connect_errno) {
    	printf("Connect failed: %s\n", $mysqli->connect_error);
    	exit();
	}

if (isset($_GET["add"])) {
	print_r($_POST["task"]);
	$toProcess = explode(" ",$_POST["task"]);
	echo count($toProcess);
	$project = "";
	$labels = [];
	$taskName = "";
	for ($i = 0; $i < count($toProcess); $i++) {
		echo "aa";
		if (strpos($toProcess[$i], "#") === 0) {
			// labels			
			echo "labels";
			if (!is_numeric(substr($toProcess[$i], 1))) // allowing to write "I'm #1"
				$labels[] = substr($toProcess[$i], 1);
		} else if (strpos($toProcess[$i], "@") === 0) {
			// project
			echo "project";
			$project = substr($toProcess[$i], 1);
		} else {
			echo "else";
			$taskName .= " ".$toProcess[$i];
			echo " tn:".$taskName;
		}
	}
	$preparedLabels = implode(",", $labels);	
	$deadline = new TDLDeadline();
	$deadline->fromForm($_POST["deadline"]);
	// Needs to parse if it is repeatable deadline or once in a lifetime
	// ToDo query db for user's todo table
	echo "{ 1".$taskName." 2".$project." 3". implode(",",$labels)." 4". $deadline->getDeadline()." 5". $deadline->getRepeat()." }";
	
	if ($stmt = $mysqli->prepare("INSERT INTO ".$_SESSION["username"]."_tasks (name, project, labels, deadline, repeatDeadline) VALUES (?, ?, ?, ?, ?);")) {
		
		$stmt->bind_param("sssis", $taskName, $project, $stmtLabels, $stmtDeadline, $stmtRepeat);
		$stmtLabels = implode(",",$labels);
		$stmtDeadline = $deadline->getDeadline();
		$stmtRepeat = $deadline->getRepeat();
		$stmt->execute();
		printf("Error: %s.\n", $stmt->error);
		$stmt->close();
		
	} else {
		echo "ueue";
	}
	
}

if (isset($_GET["done"]) && isset($_POST["toBeRemoved"])) {	 
	 // Get info about task from TASKS table
	 if ($stmt = $mysqli->prepare("SELECT id, taskname, project, labels, repeat FROM TASKS WHERE id=?;")) {
	 	$stmt->bind_param("i", $_POST["toBeRemoved"]);
	 	$stmt->execute();
		$stmt->bind_result($id, $name, $project, $labels, $repeat);
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