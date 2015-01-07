<?php
/*
 *  File which holds form for adding projects and labels
 */
require("TDLMenu.php");
?>
<div id="tasks" class="flex-child flex-parent flex-column">
    <?php /* Edit task start */ ?>
    <div class="flex-child" id="edit-probel">
        <form action="TDLProcessProbel.php?action=addProbel" method="post">
            <input type="hidden" name="id" />
            <input type="text" name="name" />
            <select name="type">
                <option value="label">label</option>
                <option value="project">project</option>
            </select>
            <input type="submit" value="Update Project/Label" />
        </form>
    </div>
    <?php /* Edit task end */ ?>
</div>
</div>