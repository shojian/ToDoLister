<?php

/*
 *  File for processing creation and modification of tasks
 */
session_start();
require_once('TDLConfig.php');
require_once('TDLDeadline.php');
require_once('TDLTaskClass.php');
$mysqli = new mysqli(TDL_DBURI, TDL_DBUSER, TDL_DBPASS, TDL_DBNAME);

/* check connection */
if ($mysqli->connect_errno) {
    header("Location: " . TDL_PATH."?err=system");
}
$getAction = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);
$toBeRemoved = filter_input(INPUT_GET, "toBeRemoved", FILTER_SANITIZE_STRING);
if ($toBeRemoved == null) {
    $toBeRemoved = false;
}
if ($getAction == "add") {
    $task = new TDLTaskClass(trim(filter_input(INPUT_POST, "task", FILTER_SANITIZE_STRING)), filter_input(INPUT_POST, "deadline", FILTER_SANITIZE_STRING), $mysqli, $_SESSION["username"]);
    if ($stmt = $mysqli->prepare("INSERT INTO " . $_SESSION["username"] . "_tasks (name, project, labels, deadline, repeatDeadline) VALUES (?, ?, ?, ?, ?);")) {

        $stmt->bind_param("sssis", $taskName, $project, $stmtLabels, $stmtDeadline, $stmtRepeat);
        $taskName = $task->getTaskName();
        $project = $task->getProject();
        $stmtLabels = implode(",", $task->getLabels());
        $stmtDeadline = $task->getDeadline();
        $stmtRepeat = $task->getRepeat();
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

if ($getAction == "updateTask") {
    echo "aa";
    $task = new TDLTaskClass(trim(filter_input(INPUT_POST, "task", FILTER_SANITIZE_STRING)), filter_input(INPUT_POST, "deadline", FILTER_SANITIZE_STRING),$mysqli, $_SESSION["username"]);
    if ($stmt = $mysqli->prepare("UPDATE " . $_SESSION["username"] . "_tasks SET name=?, project=?, labels=?, deadline=?, repeatDeadline=? WHERE id=?;")) {

        $stmt->bind_param("sssisi", $taskName, $project, $stmtLabels, $stmtDeadline, $stmtRepeat, $taskId);
        $taskName = $task->getTaskName();
        $project = $task->getProject();
        $stmtLabels = implode(",", $task->getLabels());
        $stmtDeadline = $task->getDeadline();
        $stmtRepeat = $task->getRepeat();
        $taskId = filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING);
        if ($stmt->execute()) {
            $stmt->close();
            $mysqli->close();
            header("Location: " . TDL_PATH);
        } else {
            $stmt->close();
            $mysqli->close();
            header("Location: " . TDL_PATH."?err=update");
        }
    } else {
        $mysqli->close();
        header("Location: " . TDL_PATH."?err=system");
    }
}

if (($getAction == "done") && $toBeRemoved) {
    // Get info about task from TASKS table
    $nameDoubleTuple = array();
    if ($stmt = $mysqli->prepare("SELECT name, repeatDeadline, deadline FROM " . $_SESSION["username"] . "_tasks WHERE id=?;")) {
        $stmt->bind_param("i", $toBeRemoved);
        $stmt->execute();
        $stmt->bind_result($name, $repeat, $fetchedDeadline);
        while ($stmt->fetch()) :
            $nameDoubleTuple[] = array($name, $repeat, $fetchedDeadline);
        endwhile;
        $stmt->close();
    }
    if ($stmtIns = $mysqli->prepare("INSERT INTO tsukasa_completed (name, date) VALUES (?, ?);")) {
        $stmtIns->bind_param("si", $name, $completedDate);
        $completedDate = time();
        $stmtIns->execute();
        $stmtIns->close();
    }
    foreach ($nameDoubleTuple as $value) {
        if (strlen($value[1]) > 0) {
            $deadline = new TDLDeadline();
            if ($stmtUp = $mysqli->prepare("UPDATE " . $_SESSION["username"] . "_tasks SET deadline=? WHERE id=?;")) {
                $stmtUp->bind_param("ii", $dl, $toBeRemoved);
                $dl = $deadline->getNextDeadline($value[1], $value[2]); // to be created
                $stmtUp->execute();
                $stmtUp->close();
            }
        } else {
            if ($stmtDel = $mysqli->prepare("DELETE FROM " . $_SESSION["username"] . "_tasks WHERE id=?;")) {
                $stmtDel->bind_param("i", $toBeRemoved);
                $stmtDel->execute();
                $stmtDel->close();
            }
        }
    }
    $mysqli->close();
    header("Location: " . TDL_PATH);
}

if ($getAction == "remove") {
    /*
     *  id cannot be post. It can in theory but just to be on safe side POST is ok, DELETE would be better
     */
    if ($stmt = $mysqli->prepare("DELETE FROM " . $_SESSION["username"] . "_tasks WHERE id=? LIMIT 1")) {
        $stmt->bind_param("i", $toBeRemoved);
        $stmt->execute();
        $stmt->close();
    }
    $mysqli->close();
    header("Location: " . TDL_PATH);
}

?>