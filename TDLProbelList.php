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
        $query = "SELECT id, type, name FROM " . $_SESSION["username"] . "_probels WHERE type=?;";
        $toBind = true;
        break;
    default:
        $query = "SELECT id, type, name FROM " . $_SESSION["username"] . "_probels ORDER BY type;";
}
?>
<div id="probels" class="flex-child flex-parent flex-column">
    <?php
    $labels = array();
    $projects = array();
    if ($stmt = $mysqli->prepare($query)) :
        if ($toBind) {
            $stmt->bind_param('s', $type);
        }
        $stmt->execute();
        $stmt->bind_result($id, $type, $name);
        ?>
        <div class="flex-parent flex-child flex-column" id="task-list">
            <?php
            while ($stmt->fetch()) :
                if ($type == "label") {
                    $labels[] = array($id, $name);
                } elseif ($type == "project") {
                    $projects[] = array($id, $name);
                }
            endwhile;
            $stmt->close();
        endif;
        if (count($projects) > 0) {
            ?><h1>Projects</h1><?php
        }
        foreach ($projects as $value) : ?>
            <div class="task-item flex-child flex-parent">
                <div class="flex-child">
                    <div class="flex-child flex-parent flex-column text-info">
                        <div class="flex-child name-info"><?php echo $value[1]; ?></div>
                    </div>
                    <!-- not good solution, dangerous even -->
                    <div>
                        <a href="?action=probelEdit&id=<?php echo $value[0]; ?>">Edit</a>
                        <a href="TDLProcessProbel.php?action=removeProbel&id=<?php echo $value[0]; ?>">Delete</a>				
                    </div>
                </div>
            </div>
        <?php
        endforeach;
        if (count($labels) > 0) {
            ?><h1>Labels</h1><?php
        }
        foreach ($labels as $value) : ?>
            <div class="task-item flex-child flex-parent">
                <div class="flex-child">
                    <div class="flex-child flex-parent flex-column text-info">
                        <div class="flex-child name-info"><?php echo $value[1]; ?></div>
                    </div>
                    <!-- not good solution, dangerous even -->
                    <div>
                        <a href="?action=probelEdit&id=<?php echo $value[0]; ?>">Edit</a>
                        <a href="TDLProcessProbel.php?action=removeProbel&id=<?php echo $value[0]; ?>">Delete</a>				
                    </div>
                </div>
            </div>
        <?php
        endforeach;
            ?>
        </div>
</div>
</div>
