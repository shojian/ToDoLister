<?php
/**
 *  Templates list file. Shows list of templates.
 */

require("TDLMenu.php");
if ($stmt = $mysqli->prepare("SELECT id, type, name FROM ".$_SESSION['username']."_templates;")) {
    $stmt->execute();
    $stmt->bind_result($id, $type, $name);
    $counter = 0;
    ?>
        <div class="flex-parent flex-child flex-column" id="task-list">
            <div class="flex-child add-new-template">
            <a href="?action=addNewTemplate">Add new template</a>
            </div>
        <?php while ($stmt->fetch()) : 
                if (($counter++ % 2) == 0) : ?>
                <div class="task-item flex-child flex-parent even-item">
                <?php else : ?>
                <div class="task-item flex-child flex-parent odd-item">
                <?php endif; ?>
                    <div class="flex-child flex-parent flex-column text-info">
                        <div class="flex-child name-info"><?php echo $name; echo $type; ?></div>
                    </div>
                        <!-- not good solution, dangerous even -->
                        <div class="templates-item-meta flex-child">
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