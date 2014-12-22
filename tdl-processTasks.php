<?php
	/*
	 *  File for processing creation and modification of tasko
	 */
session_start();
require_once('tdl-config.php');
require_once('tdl-taskClass.php');

$mysqli = new mysqli(TDL_DBURI, TDL_DBUSER, TDL_DBPASS, TDL_DBNAME);
	
	/* check connection */
	if ($mysqli->connect_errno) {
    	redirectError("conn");
	}
$getAction = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);
$toBeRemoved = filter_input(INPUT_GET, "toBeRemoved", FILTER_SANITIZE_STRING);
if ($toBeRemoved == null) {
    $toBeRemoved = false;
}
if ($getAction == "add") {
	$task = new Task(filter_input(INPUT_POST, "task", FILTER_SANITIZE_STRING),
					 filter_input(INPUT_POST, "deadline", FILTER_SANITIZE_STRING))
	
	if ($stmt = $mysqli->prepare("INSERT INTO ".$_SESSION["username"]."_tasks (name, project, labels, deadline, repeatDeadline) VALUES (?, ?, ?, ?, ?);")) {
		
		$stmt->bind_param("sssis", $taskName, $project, $stmtLabels, $stmtDeadline, $stmtRepeat);
		$taskName = $task->getTaskName();
		$project = $task->getProject();		
		$stmtLabels = implode(",",$task->getLabels());
		$stmtDeadline = $task->getDeadline();
		$stmtRepeat = $task->getRepeat();
		if($stmt->execute()) {		
			$stmt->close();	
			redirectInserted();
		} else {
			$stmt->close();	
			redirectError("insertError");
		}
		
	} else {
		redirectError("insertError");
	}
	
}

if ($getAction == "update") {
	$task = new Task(filter_input(INPUT_POST, "task", FILTER_SANITIZE_STRING),
					 filter_input(INPUT_POST, "deadline", FILTER_SANITIZE_STRING))
	
	if ($stmt = $mysqli->prepare("UPDATE ".$_SESSION["username"]."_tasks SET name=?, project=?, labels=?, deadline=?, repeatDeadline=? WHERE id=?;")) {
		
		$stmt->bind_param("sssis", $taskName, $project, $stmtLabels, $stmtDeadline, $stmtRepeat, $taskId);
		$taskName = $task->getTaskName();
		$project = $task->getProject();		
		$stmtLabels = implode(",",$task->getLabels());
		$stmtDeadline = $task->getDeadline();
		$stmtRepeat = $task->getRepeat();
		$taskId = filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING);
		if($stmt->execute()) {		
			$stmt->close();	
			redirectUpdated();
		} else {
			$stmt->close();	
			redirectError("insertError");
		}
		
	} else {
		redirectError("insertError");
	}
	
}

if (($getAction == "done") && $toBeRemoved) {	 
	 // Get info about task from TASKS table
	 if ($stmt = $mysqli->prepare("SELECT id, taskname, project, labels, repeat FROM TASKS WHERE id=?;")) {
	 	$stmt->bind_param("i", $toBeRemoved);
	 	$stmt->execute();
		$stmt->bind_result($id, $name, $project, $labels, $repeat);
	 // Add info to Done table
	 	 
	 // If non-renewable remove task from TASKS table
	 
	 // otherwise renew task in TASKS table
	 } else {
	 	// Throw some error
	 	
	 }
	 
}

if ($getAction == "remove") {
	/*
	 *  id cannot be post. It can in theory but just to be on safe side POST is ok, DELETE would be better
	 */
	 if ($stmt = $mysqli->prepare("DELETE FROM ".$_SESSION["username"]."_tasks WHERE id=? LIMIT 1")) {
	 	$stmt->bind_param("i", $toBeRemoved);
	 	$stmt->execute();
	 	$stmt->close();
	 }
}

/* Redirect functions */

function redirectInserted() {
	
}

function redirectError($err) {
	
}

?>