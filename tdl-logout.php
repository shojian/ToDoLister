<?php
	/*
	 *  File which handles logging out of application
	 */
	 
	session_abort();
	header("Location: ".TDL_PATH);
?>