<?php
/*
 *  Index file for ToDoLister
 */
session_start();
session_regenerate_id();
//ob_start();
require_once("TDLConfig.php");
require_once("TDLHeader.php");
$requiresLogin = true;
if (!isset($_COOKIE["userData"]) || !isset($_SESSION["username"])) {
	require("TDLLogin.php");
} else {
	$mysqli = new mysqli(TDL_DBURI, TDL_DBUSER, TDL_DBPASS, TDL_DBNAME);
	
	// check connection 
	if ($mysqli->connect_errno) {
    	printf("Connect failed: %s\n", $mysqli->connect_error);
    	exit();
	}
	// create a prepared statement 
	if ($stmt = $mysqli->prepare("SELECT username FROM USERS WHERE username=?")) {
		$stmt->bind_param('s', $_COOKIE["userData"]);
		$stmt->execute();
		$stmt->bind_result($user);
		while ($stmt->fetch()) {
			if (!empty($user)) {
				$requiresLogin = true;
			}
		}
		$stmt->close();
	}
	if ($requiresLogin) {
		$getAction = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);
		if ($getAction == "edit") {                        
			require("TDLEditTask.php");
		} else {
			require("TDLTaskList.php");
		}
	} else {		
		require("TDLLogin.php");
	}
	$mysqli->close();
}
require_once("TDLFooter.php");
//ob_end_flush();
?>