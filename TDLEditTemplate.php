<?php
/*
 *  File which holds form for editing templates
 */
$getId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
require("TDLMenu.php");
?>
<div id="tasks" class="flex-child flex-parent flex-column">    
    <?php /* Edit task start */ ?>
    <div class="flex-child edit-template" id="edit-template">
        <h1>Edit task</h1>
        <?php
        $query = "SELECT id,name,template FROM " . $_SESSION["username"] . "_templates WHERE id=?;";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('i', $getId);
            $stmt->execute();
            $stmt->bind_result($id, $name, $template);
            while ($stmt->fetch()) : ?>
                <form action="TDLProcessTemplates.php?action=updateTemplate" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <input type="text" name="task" class="text-field" value="<?php echo trim($name); ?>"/>
                    <textarea name="template" class="template-area"><?php echo trim($template) ?></textarea>
                    <input type="submit" class="submit-btn" value="Update task" />
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