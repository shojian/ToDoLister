<?php
    /*
     *  File which generates list of projects and/or labels
     */

require("TDLMenu.php");	
$type = filter_input(INPUT_GET, "type", FILTER_SANITIZE_STRING);
$toBind = false;
switch ($type) {
    case "label":
    case "project":
        $query = "SELECT id, type, name FROM ".$_SESSION["username"]."_probels WHERE type=?;";
        $toBind = true;
        break;
    default:
        $query = "SELECT id, type, name FROM ".$_SESSION["username"]."_probels ORDER BY type;";
}
?>
<div id="probels" class="flex-child flex-parent flex-column">
    <?php
        if ($stmt = $mysqli->prepare($query)) {
            if ($toBind) {
                $stmt->bind_param('s', $type);
            }
            $stmt->execute();
            $stmt->bind_result($id, $type, $name);
        }
    ?>
</div>
