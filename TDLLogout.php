<?php
	/*
	 *  File which handles logging out of application
	 */
	 session_start();
	 require_once("TDLConfig.php");
	session_destroy();
	header("Location: ".TDL_PATH);
?>