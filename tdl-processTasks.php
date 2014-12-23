<?php
	/*
	 *  File for processing creation and modification of tasko
	 */
session_start();
require_once('tdl-config.php');
require_once('tdl-deadline.php');
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
	$task = new TaskClass(filter_input(INPUT_POST, "task", FILTER_SANITIZE_STRING),
					 filter_input(INPUT_POST, "deadline", FILTER_SANITIZE_STRING));
	if ($stmt = $mysqli->prepare("INSERT INTO ".$_SESSION["username"]."_tasks (name, project, labels, deadline, repeatDeadline) VALUES (?, ?, ?, ?, ?);")) {
		
		$stmt->bind_param("sssis", $taskName, $project, $stmtLabels, $stmtDeadline, $stmtRepeat);
		$taskName = $task->getTaskName();
		$project = $task->getProject();		
		$stmtLabels = implode(",",$task->getLabels());
		$stmtDeadline = $task->getDeadline();
		$stmtRepeat = $task->getRepeat();
		if($stmt->execute()) {		
			$stmt->close();	
			$mysqli->close();
			redirectInserted();
		} else {
			$stmt->close();	
			$mysqli->close();
			redirectError("insertError");
		}
		
	} else {
		$mysqli->close();
		redirectError("insertError");
	}
	
}

if ($getAction == "updateTask") {	
	$task = new TaskClass(filter_input(INPUT_POST, "task", FILTER_SANITIZE_STRING),
					 filter_input(INPUT_POST, "deadline", FILTER_SANITIZE_STRING));
	if ($stmt = $mysqli->prepare("UPDATE ".$_SESSION["username"]."_tasks SET name=?, project=?, labels=?, deadline=?, repeatDeadline=? WHERE id=?;")) {
		
		$stmt->bind_param("sssisi", $taskName, $project, $stmtLabels, $stmtDeadline, $stmtRepeat, $taskId);
		$taskName = $task->getTaskName();
		$project = $task->getProject();		
		$stmtLabels = implode(",",$task->getLabels());
		$stmtDeadline = $task->getDeadline();
		$stmtRepeat = $task->getRepeat();
		$taskId = filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING);
		if($stmt->execute()) {		
			$stmt->close();	
			$mysqli->close();
			redirectUpdated();
		} else {
			$stmt->close();	
			$mysqli->close();
			redirectError("updateError");
		}
		
	} else {
		$mysqli->close();
		redirectError("updateError");
	}
	
}

if (($getAction == "done") && $toBeRemoved) {	 
	 // Get info about task from TASKS table
	 if ($stmt = $mysqli->prepare("SELECT taskname, repeat FROM TASKS WHERE id=?;")) {
	 	$stmt->bind_param("i", $toBeRemoved);
	 	$stmt->execute();
		$stmt->bind_result($name, $repeat);
		while ($stmt->fetch()) : 
			if ($stmtIns = $mysqli->prepare("INSERT INTO ".$_SESSION["username"]."_completed (name, date) VALUES (?, ?);")) {
				$stmtIns->bind_param("si", $name, $completedDate);
				$completedDate = time();
				$stmtIns->execute();
				$stmtIns->close();
			}
			if (strlen($repeat) > 0) {
				$deadline = new TDLDeadline();
				$deadline->fromForm($repeat);
				if ($stmtUp = $mysqli->prepare("UPDATE ".$_SESSION["username"]."_completed SET deadline=? WHERE id=?;")) {
					$stmtUp->bind_param("ii", $dl, $toBeRemoved);
					$dl = $deadline->getNextDeadline(); // to be created
					$stmtUp->execute();
					$stmtUp->close();
				}
			} else {
				if ($stmtDel = $mysqli->prepare("DELETE FROM ".$_SESSION["username"]."_completed WHERE id=?;")) {
					$stmtDel->bind_param("i", $toBeRemoved);
					$stmtDel->execute();
					$stmtDel->close();
				}
			}
		endwhile;
		$stmt->close();
	 } else {
	 	// Throw some error
	 	
	 }
	 $mysqli->close();
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
	 $mysqli->close();
}

/* Redirect functions */

function redirectInserted() {
	
}

function redirectError($err) {
	
}

?>