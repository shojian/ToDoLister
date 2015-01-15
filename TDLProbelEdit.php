<?php
/*
 *  File which holds form for editing projects and labels
 */
$getId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
require("TDLMenu.php");
?>
<div id="tasks" class="flex-child flex-parent flex-column">
    <?php /* Edit task start */ ?>
    <div class="flex-child addedit-probel" id="edit-probel">
        <h1>Edit project/label</h1>
        <?php
        $query = "SELECT id, type, name FROM " . $_SESSION["username"] . "_probels WHERE id=?;";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('i', $getId);
            $stmt->execute();
            $stmt->bind_result($probelId, $probelType, $probelName);
            while ($stmt->fetch()) : ?>
                <form action="TDLProcessProbel.php?action=updateProbel&id=<?php echo $probelId; ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo $probelId; ?>" />
                    <input type="text" name="name" class="text-field" value="<?php echo $probelName; ?>"/>
                    <select class="date-field" name="type">
                        <option value="label" <?php if ($probelType == "label") { echo "selected=\"selected\""; } ?>>label</option>
                        <option value="project" <?php if ($probelType == "project") { echo "selected=\"selected\""; } ?>>project</option>
                    </select>
                    <input type="submit" value="Update Project/Label" class="submit-btn" />
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