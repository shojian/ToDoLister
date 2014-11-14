<?php
	/*
	 *  Task list file. Shows tasks.
	 */
$tomorrow = getdate(time() + (24*60*60)); 
$dst = -1;
if (date('I', time())) {    
	$dst = 1;
} else {     
	$dst = 0;
}
$deadline = mktime(0, 0, 0, $tomorrow["mon"], $tomorrow["mday"], $tomorrow["year"], $dst);
if (isset($_GET["deadline"]))
	$deadline = $_GET["deadline"];
	/* Sigh, at the moment only single user. Have to think how to scale it to
	   multi user environment. Certainly a To Do. Having tasks for users would be
	   messy and slow. */
if ($stmt = $mysqli->prepare("SELECT * FROM TASKS WHERE deadline<=?")) {
		$stmt->bind_param('i', $deadline);
		$stmt->execute();
		$stmt->bind_result($id, $name, $project, $labels, $deadline);
		?>
		<div class="flex-parent" id="task-list">
		<?php
		while ($stmt->fetch()) : ?>
			<div class="task-item">
				
			</div>
		<? endwhile; ?>
		</div>
		<?php
		$stmt->close();
	}
	
?>