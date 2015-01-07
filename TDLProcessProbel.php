<?php
/*
 *  File for processing creation and modification of projects
 */

session_start();
require_once('TDLConfig.php');

$mysqli = new mysqli(TDL_DBURI, TDL_DBUSER, TDL_DBPASS, TDL_DBNAME);

/* check connection */
if ($mysqli->connect_errno) {
    header("Location: " . TDL_PATH."?err=system");
}
$getAction = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);
$type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_STRING);
$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
if ($toBeRemoved == null) {
    $toBeRemoved = false;
}

if ($getAction == "add") {
    if ($stmt = $mysqli->prepare("INSERT INTO ".$_SESSION['username']."_probels (type, name) VALUES (?, ?);")) {
        $stmt->bind_param("ss", $type, $name);
        if ($stmt->execute()) {
            $stmt->close();
            $mysqli->close();
            header("Location: " . TDL_PATH);
        } else {
            $stmt->close();
            $mysqli->close();
            header("Location: " . TDL_PATH."?err=insert");
        }
    } else {
        $mysqli->close();
        header("Location: " . TDL_PATH."?err=system");
    }
}

if ($getAction == "update") {
    if ($stmt = $mysqli->prepare("UPDATE ".$_SESSION['username']."_probels SET name=?, type=? WHERE id=?;")) {
        $stmt->bind_param("ssi", $name, $type, $id);
        if ($stmt->execute()) {
            $stmt->close();
            $mysqli->close();
            header("Location: " . TDL_PATH);
        } else {
            $stmt->close();
            $mysqli->close();
            header("Location: " . TDL_PATH."?err=insert");
        }
    } else {
        $mysqli->close();
        header("Location: " . TDL_PATH."?err=system");
    }
}

if ($getAction == "remove") {
    if ($stmt = $mysqli->prepare("DELETE FROM " . $_SESSION["username"] . "_probels WHERE id=? LIMIT 1")) {
        $stmt->bind_param("i", $toBeRemoved);
        $stmt->execute();
        $stmt->close();
    }
    $mysqli->close();
    header("Location: " . TDL_PATH);
}

?>