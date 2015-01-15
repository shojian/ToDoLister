<?php
/*
 *  File which holds form for adding projects and labels
 */
require("TDLMenu.php");
$probelType = filter_input(INPUT_GET, "default", FILTER_SANITIZE_STRING);
?>
<div id="tasks" class="flex-child flex-parent flex-column">
    <?php /* Edit task start */ ?>
    <div class="flex-child addedit-probel" id="edit-probel">
        <h1>Add new project/label</h1>
        <form action="TDLProcessProbel.php?action=addProbel" method="post">
            <input type="hidden" name="id" />
            <input type="text" name="name" class="text-field" />
            <select class="date-field" name="type">
                <option value="label" <?php if ($probelType == "label") { echo "selected=\"selected\""; } ?>>label</option>
                <option value="project" <?php if ($probelType == "project") { echo "selected=\"selected\""; } ?>>project</option>
            </select>
            <input type="submit" value="Add new Project/Label" class="submit-btn" />
        </form>
    </div>
    <?php /* Edit task end */ ?>
</div>
</div>