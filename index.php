<?php
/*
 *  Index file for ToDoLister
 */
session_start();
ob_start();
require_once('tdl-config.php');
require_once("tdl-header.php");
$requiresLogin = true;
if (!isset($_COOKIE["userData"]) || !isset($_SESSION["username"])) {
	require("tdl-login.php");
} else {
	$mysqli = new mysqli(TDL_DBURI, TDL_DBUSER, TDL_DBPASS, TDL_DBNAME);
	
	/* check connection */
	if ($mysqli->connect_errno) {
    	printf("Connect failed: %s\n", $mysqli->connect_error);
    	exit();
	}
	/* create a prepared statement */
	if ($stmt = $mysqli->prepare("SELECT username FROM USERS WHERE username=? AND sessID=?")) {
		$stmt->bind_param('ss', $_COOKIE["userData"]["username"], SID);
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
		require("tdl-tasklist.php");
	} else {
		require("tdl-login.php");
	}
	$mysqli->close();
}
require_once("tdl-footer.php");
ob_end_flush();
?>