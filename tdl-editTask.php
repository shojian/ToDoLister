<?php
	/*
	 *  File which holds form for editing task
	 */
	$getId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
	require("tdl-menu.php");
?>
	<div id="tasks" class="flex-child flex-parent flex-column">
		<?php /* Edit task start */ ?>
		<div class="flex-child" id="edit-task">
		<?php 				
				$query = "SELECT id,name,project,labels,deadline FROM ".$_SESSION["username"]."_TASKS WHERE id=?;";
				if ($stmt = $mysqli->prepare($query)) {
					$stmt->bind_param('i', $getId);
					$stmt->execute();
					$stmt->bind_result($id, $name, $project, $labels, $deadline);									
					while ($stmt->fetch()) : 
						$projectFinal = "@".$project;					
						if (strlen($labels) > 0) {
							$labelsArr = explode(",", $labels);
							for ($i = 0; $i < count($labelsArr); $i++) {
								$labelsArr[$i] = "#".$labelsArr[$i];
							}	
						}
					?>
			<form action="tdl-processTasks.php?action=updateTask" method="post">
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<input type="text" name="task" value="<?php echo $name." ".$projectFinal." ".implode(' ', $labelsArr); ?>"/>
				<input type="date" name="deadline" placeholder="dd. mm. yyyy" value="<?php echo date('j. m. Y', $deadline-1) ?>" />
				<input type="submit" value="Update task" />
			</form>
			<?php endwhile; 
			$stmt->close();
			}?>
		</div>
		<?php /* Edit task end */ ?>
	</div>
</div>