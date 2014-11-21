<?php
	/*
	 *  File where app handles logging in
	 */
	 session_start();
	 session_regenerate_id();
	 require_once("tdl-config.php");
	 if ((strlen($_POST["botBlocker"]) == 0) && (SID == $_POST['lock'])){
	 // bots will try to fill all inputs. botBlocker input will be hidden with CSS
	 	$mysqli = new mysqli(TDL_DBURI, TDL_DBUSER, TDL_DBPASS, TDL_DBNAME);
	 	$user = $_POST["username"];
	 	$pass = password_hash($_POST["password"], PASSWORD_BCRYPT);
		if($stmt = $mysql->prepare("SELECT password FROM USERS WHERE username=?")) {
			$stmt->bind_param("s", $user);
			$stmt->execute();
			$stmt->bind_result($password);
			while ($stmt->fetch()) {
				if ($password == $pass) {
					// mental note: look into PHP security more deeply
					setcookie('userData', $user, time()+(30*60));
					$_SESSION["username"] = $user;
					header("Location: http://".$_SERVER['SERVER_NAME']);
				}
			}
		} else {
			echo "Unable to log in.";
		}
	 } else {
	 	echo "Please, try again bot."
	 }
?>