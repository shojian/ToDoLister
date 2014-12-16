<?php
	/*
	 *  File which handles logging out of application
	 */
	 session_start();
	 require_once("tdl-config.php");
	session_destroy();
	header("Location: ".TDL_PATH);
?>