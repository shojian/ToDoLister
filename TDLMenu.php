<?php
/*
 *  File which holds menu
 */
/* Start menu */
?>	
<div class="flex-parent main"> 	
    <div class="flex-child flex-parent flex-column" id="menu">
        <h1 class="flex-child">To Do Lister</h1>
        <div class="flex-child time-list" id="time-list">
            <ul> <?php
                // *sigh* verbal stuff here to be redone 
                $tomorrow = getdate(time() + (24 * 60 * 60));
                $weekLater = getdate(time() + (7 * 24 * 60 * 60) + (24 * 60 * 60));
                $dst = -1;
                if (date('I', time())) {
                    $dst = 1;
                } else {
                    $dst = 0;
                }
                $deadline = mktime(0, 0, 0, $tomorrow["mon"], $tomorrow["mday"], $tomorrow["year"], $dst);
                $weekLaterDeadline = mktime(0, 0, 0, $weekLater["mon"], $weekLater["mday"], $weekLater["year"], $dst);
                ?>
                <li><a href="?deadline=<?php echo time(); ?>">Overdue</a></li>
                <li><a href="?deadline=<?php echo $deadline; ?>">Today</a></li>
                <li><a href="?deadline=<?php echo time() + (2 * 24 * 60 * 60); ?>">Next 48 hours</a></li>
                <li><a href="?deadline=<?php echo $weekLaterDeadline; ?>">Next 7 days</a></li>
            </ul>
        </div>
        <?php
        $projectList = array();
        $labelList = array();
        if ($stmt = $mysqli->prepare("SELECT type, name FROM " . $_SESSION['username'] . "_probels ORDER BY type;")) {
            $stmt->execute();
            $stmt->bind_result($type, $name);
            while ($stmt->fetch()) :
                if ($type == "project") {
                    $projectList[] = $name;
                } elseif ($type == "label") {
                    $labelList[] = $name;
                }
            endwhile;
        }
        ?><div class="flex-child probel-list" id="probel-list">
                <h2>Projects</h2>
                <a href="?action=probelAdd&default=project">new</a> <a href="?action=probelList&type=project">edit</a>
                <?php if (count($projectList) > 0) : ?>
                <ul>
                <?php
                endif;
                foreach ($projectList as $name) :
                    ?><li><a href="?project=<?php echo $name; ?>"><?php echo $name; ?></a></li><?php
                endforeach;
                if (count($projectList) > 0) :
                    ?>
                </ul>
                <?php endif; ?>                
                <h2>Labels</h2>
                <a href="?action=probelAdd&default=label">new</a> <a href="?action=probelList&type=label">edit</a>
                <?php if (count($labelList) > 0) :  ?>
                <ul>
                <?php
                endif;
                foreach ($labelList as $name) :
                    ?><li><a href="?label=<?php echo $name; ?>"><?php echo $name; ?></a></li><?php
                endforeach;
                if (count($labelList) > 0) : ?>
                </ul>
    <?php endif;
    ?></div>		
        <a href="<?php echo TDL_PATH ?>TDLLogout.php">Log out</a>
    </div> <?php
    /* End of menu part */
    ?>