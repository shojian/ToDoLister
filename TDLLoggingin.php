<?php

/*
 *  File where app handles logging in
 */
session_start();
session_regenerate_id();
require_once("TDLConfig.php");
if ((strlen($_POST["botBlocker"]) == 0) && (SID == $_POST['lock'])) {
    // bots will try to fill all inputs. botBlocker input will be hidden with CSS

    $mysqli = new mysqli(TDL_DBURI, TDL_DBUSER, TDL_DBPASS, TDL_DBNAME) or die("Could not connect.");
    $user = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    if ($stmt = $mysqli->prepare("SELECT password FROM users WHERE username=?")) {
        echo "aa";
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->bind_result($password);
        print_r($stmt);
        $notLogged = "0";
        echo $notLogged;
        while ($stmt->fetch()) {
            if (password_verify($_POST["password"], $password)) {
                // mental note: look into PHP security more deeply
                setcookie('userData', $user, time() + (30 * 60));
                $_SESSION["username"] = $user;
                header("Location: " . TDL_PATH);
            } else {
                header("Location: " . TDL_PATH . "?err=user");
            }
        }
        echo $notLogged;
    } else {
        header("Location: " . TDL_PATH . "?err=system");
    }
} else {
    header("Location: " . TDL_PATH . "?err=bot");
}
?>