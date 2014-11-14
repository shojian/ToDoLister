<?php
	/*
	 *  Task list file. Shows tasks.
	 */
?>	
<div class="flex-parent"> 
	<div class="flex-child flex-parent" id="meta-info">
		<div class="flex-child" id="time-list">
		
		</div>
		<div class="flex-child" id="projects-list">
		
		</div>
		<div class="flex-child" id="labels-list">
		
		</div>		
	</div>
<?php	 
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
		<div class="flex-parent flex-child" id="task-list">
		<?php
		while ($stmt->fetch()) : ?>
			<div class="task-item flex-child">
				<div class="flex-child done-mark"><input type="checkbox" name="task-no[<?php echo $id; ?>]" /></div>
				<div class="flex-child name-info"><?php echo $name; ?></div>
				<div class="flex-child meta-info"><?php echo $project." ".$labels; ?></div>
				<div class="flex-child deadline"><?php echo $deadline; /*To be formatted properly*/?></div>
			</div>
		<? endwhile; ?>
		</div>
		<?php
		$stmt->close();
	}	?>
</div>