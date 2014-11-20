<?php
	/*
	 *  File where app handles logging in
	 */
	 session_start();
	 require_once("tdl-config.php");
	 if (strlen($_POST["botBlocker"]) == 0) {
	 // bots will try to fill all inputs. botBlocker input will be hidden with CSS
	 	$mysqli = new mysqli(TDL_DBURI, TDL_DBUSER, TDL_DBPASS, TDL_DBNAME);
	 	$user = $_POST["username"];
	 	$pass = password_hash($_POST["password"], PASSWORD_BCRYPT);
		if($stmt = $mysql->prepare("SELECT * FROM USERS WHERE username=? AND password=?")) {
			$stmt->bind_param("ss", $user, $pass);
			$stmt->execute();
		}
	 } else {
	 	echo "Please, try again bot."
	 }
?>