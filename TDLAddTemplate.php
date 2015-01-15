<?php
/*
 *  File which holds form for adding templates
 */
$getId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
require("TDLMenu.php");
?>
<div id="templates" class="flex-child flex-parent flex-column">    
    <?php /* Edit task start */ ?>
    <div class="flex-child add-template" id="add-template">
        <h1>Add template</h1>
                <form action="TDLProcessTemplates.php?action=addTemplate" method="post">
                    <input type="text" name="template" class="text-field" />
                    <textarea name="template" class="template-area"></textarea>
                    <input type="submit" class="submit-btn" value="Update task" />
                </form>
    </div>
</div>
</div>