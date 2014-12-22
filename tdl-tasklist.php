<?php
	/*
	 *  Task list file. Shows tasks.
	 */
	 
	require("tdl-menu.php");	 
?>
	<div id="tasks" class="flex-child flex-parent flex-column">
	<?php
	/* Add new task start */ ?>
	<div class="flex-child" id="add-new-task">
		<form action="tdl-processTasks.php?action=add" method="post">
			<input type="text" name="task" />
			<input type="date" name="deadline" placeholder="dd. mm. yyyy"/>
			<input type="submit" value="Add new task" />
		</form>
	</div>
	<?php
	/* Add new task end */
	/* Start of  the task listing */
$tomorrow = getdate(time() + (24*60*60)); 
$dst = -1;
if (date('I', time())) {    
	$dst = 1;
} else {     
	$dst = 0;
}
$deadline = mktime(0, 0, 0, $tomorrow["mon"], $tomorrow["mday"], $tomorrow["year"], $dst);
$queryMode;
$query = "SELECT id,name,project,labels,deadline FROM ".$_SESSION["username"]."_TASKS WHERE deadline<=?";
if (!isset($_GET["deadline"]) && isset($_GET["label"]) && !isset($_GET["project"])) {
	$query = "SELECT id,name,project,labels,deadline FROM ".$_SESSION["username"]."_TASKS WHERE labels<=?";
	$queryMode = "label";
} else if (!isset($_GET["deadline"]) && !isset($_GET["label"]) && isset($_GET["project"])) {
	$query = "SELECT id,name,project,labels,deadline FROM ".$_SESSION["username"]."_TASKS WHERE project<=?";
	$queryMode = "project";
} else {
	if (isset($_GET["deadline"])) {		
		$deadline = $_GET["deadline"];
		$queryMode = "deadline";
	}
}

if ($stmt = $mysqli->prepare($query)) {
		$stmt->bind_param('i', $deadline);
		$stmt->execute();
		$stmt->bind_result($id, $name, $project, $labels, $deadline);
		?>
		<div class="flex-parent flex-child flex-column" id="task-list">
		<input type="submit" value="Update">
		<?php
		while ($stmt->fetch()) : ?>
			<div class="task-item flex-child flex-parent">
				<div class="flex-child done-mark"><input type="checkbox" name="task-no['<?php echo $id; ?>']" /></div>
				<div class="flex-child flex-parent flex-column text-info">
					<div class="flex-child name-info"><?php echo $name; ?></div>
					<div class="flex-child meta-info"><?php echo $project." ".$labels; ?></div>
				</div>
				<?php if ($deadline < time()) : ?>
				<div class="deadline overdue">
				<?php endif; 
				if (date("G:i", $deadline) == "0:00")			
					echo date("j. m. Y", $deadline);
				else 
					echo date("G:i j. m. Y", $deadline);
				?></div>
				<!-- not good solution, dangerous even -->
				<a href="tdl-processTasks.php?action=remove&toBeRemoved=<?php echo $id; ?>">Delete</a> <a href="?action=edit&id=<?php echo $id; ?>">Edit</a>
			</div>
		<? endwhile; ?>
		</div>
		<?php
		$stmt->close();
	}	?>
	</div>
</div>