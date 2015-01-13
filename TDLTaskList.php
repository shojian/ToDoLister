<?php
/*
 *  Task list file. Shows tasks.
 */

require("TDLMenu.php");
?>
<div id="tasks" class="flex-child flex-parent flex-column">        
    <?php /* Add new task start */ ?>
    <div class="flex-child" id="add-new-task">
        <form action="TDLProcessTasks.php?action=add" method="post">
            <input type="text" name="task" />
            <input type="date" name="deadline" placeholder="dd. mm. yyyy"/>
            <input type="submit" value="Add new task" />
        </form>
    </div>
<?php
/* Add new task end */
/* Start of  the task listing */
$tomorrow = getdate(time() + (24 * 60 * 60));
$dst = -1;
if (date('I', time())) {
    $dst = 1;
} else {
    $dst = 0;
}
$deadline = mktime(0, 0, 0, $tomorrow["mon"], $tomorrow["mday"], $tomorrow["year"], $dst);
$queryMode;
$probel;
$query = "SELECT id,name,project,labels,deadline FROM " . $_SESSION["username"] . "_tasks WHERE deadline<=? ORDER BY deadline;";
if (!isset($_GET["deadline"]) && isset($_GET["label"]) && !isset($_GET["project"])) {
    $probel = filter_input(INPUT_GET, "label", FILTER_SANITIZE_STRING);
    $query = "SELECT id,name,project,labels,deadline FROM " . $_SESSION["username"] . "_tasks WHERE labels LIKE ? ORDER BY deadline;";
    $queryMode = "label";
} else if (!isset($_GET["deadline"]) && !isset($_GET["label"]) && isset($_GET["project"])) {
    $probel = filter_input(INPUT_GET, "project", FILTER_SANITIZE_STRING);
    $query = "SELECT id,name,project,labels,deadline FROM " . $_SESSION["username"] . "_tasks WHERE project=? ORDER BY deadline;";
    $queryMode = "project";
} else {
    if (isset($_GET["deadline"])) {
        $deadline = $_GET["deadline"];
        $queryMode = "deadline";
    }
}
if ($stmt = $mysqli->prepare($query)) {
    switch ($queryMode) {
        case "label":
        case "project":
            if (strlen($probel) !== 0) {
                $stmt->bind_param('s', $probel);
            }
            break;
        default:
            $stmt->bind_param('i', $deadline);
            break;
    }
    $stmt->execute();
    $stmt->bind_result($id, $name, $project, $labels, $deadline);
    $counter = 0;
    ?>
        <div class="flex-parent flex-child flex-column" id="task-list">
        <?php while ($stmt->fetch()) : 
                if (($counter++ % 2) == 0) : ?>
                <div class="task-item flex-child flex-parent even-item">
                <?php else : ?>
                <div class="task-item flex-child flex-parent odd-item">
                <?php endif; ?>
                    <div class="flex-child done-mark">
                        <!-- to be checkbox -->
                        <input type="hidden" name="task-no['<?php echo $id; ?>']" /></div>
                    <div class="flex-child flex-parent flex-column text-info">
                        <div class="flex-child name-info"><?php echo $name; ?></div>
                        <div class="flex-child meta-info"><?php echo $project . " " . $labels; ?></div>
                    </div>
        <?php if ($deadline < time()) : ?>
                        <div class="deadline overdue">
                    <?php else : ?>
                            <div class="deadline">
                        <?php
                        endif;
                        if (date("G:i:s", $deadline) == "23:59:59") {
                            echo date("j. m. Y", $deadline);
                        } else {
                            echo date("G:i j. m. Y", $deadline);
                        }
                        ?></div>
                        <!-- not good solution, dangerous even -->
                        <div>
                            <a href="TDLProcessTasks.php?action=done&toBeRemoved=<?php echo $id; ?>">Complete</a>
                            <a href="TDLProcessTasks.php?action=remove&toBeRemoved=<?php echo $id; ?>">Delete</a>
                            <a href="?action=edit&id=<?php echo $id; ?>">Edit</a>
                        </div>
                    </div>
    <?php endwhile; ?>
            </div>
                <?php
                $stmt->close();
            }
            ?>
    </div>
</div>