<?php
/*
 *  File which holds form for editing task
 */
$getId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
require("TDLMenu.php");
?>
<div id="tasks" class="flex-child flex-parent flex-column">
    <?php /* Edit task start */ ?>
    <div class="flex-child" id="edit-task">
        <?php
        $query = "SELECT id,name,project,labels,deadline FROM " . $_SESSION["username"] . "_tasks WHERE id=?;";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('i', $getId);
            $stmt->execute();
            $stmt->bind_result($id, $name, $project, $labels, $deadline);
            while ($stmt->fetch()) :
                $str = $name;
                if (strlen($project) > 0) {
                    $projectFinal = "@" . $project;
                } else {
                    $projectFinal = "";                    
                }
                if (strlen($labels) > 0) {
                    $labelsArr = explode(",", $labels);
                    for ($i = 0; $i < count($labelsArr); $i++) {
                        $labelsArr[$i] = "#" . $labelsArr[$i];
                    }
                }
                if (strlen($projectFinal) > 0) {
                    $str .= " ".$projectFinal;
                }
                if (count($labelsArr) > 0) {
                    $str .= " ".implode(' ', $labelsArr);
                }
                ?>
                <form action="TDLProcessTasks.php?action=updateTask" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <input type="text" name="task" value="<?php echo $str; ?>"/>
                    <input type="date" name="deadline" placeholder="dd. mm. yyyy" value="<?php echo date('j. m. Y', $deadline) ?>" />
                    <input type="submit" value="Update task" />
                </form>
            <?php
            endwhile;
            $stmt->close();
        }
        ?>
    </div>
<?php /* Edit task end */ ?>
</div>
</div>