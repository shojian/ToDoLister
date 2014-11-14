<?php
	/*
	 *  Task list file. Shows tasks.
	 */
	 
	 
	 /* Start menu */
?>	
<div class="flex-parent"> 
	<div class="flex-child flex-parent" id="meta-info">
		<div class="flex-child" id="time-list">
			<ul> <?php // *sigh* verbal stuff here to be redone 
				$tomorrow = getdate(time() + (24*60*60)); 
				$weekLater = getdate(time() + (7*24*60*60) + (24*60*60));
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
				<li><a href="?deadline=<?php echo time() + (2*24*60*60); ?>">Next 48 hours</a></li>
				<li><a href="?deadline=<?php echo $weekLaterDeadline; ?>">Next 7 days</a></li>
			</ul>
		</div>
		<?php
		if ($stmt = $mysqli->prepare("SELECT projects FROM USERS")) {
		$stmt->execute();
		$stmt->bind_result($projects);
		?>
		<div class="flex-child" id="projects-list">
			<ul>
			<?php while ($stmt->fetch()) :?>
				<?php 
					$projectArr = explode(",", $projects);
					for ($i = 0; $i < count($projectArr); i++):
				 ?>
				<li><a href="?project=<?php echo $projectArr[$i]; ?>"><?php echo $projectArr[$i]; ?></a></li>
			<?php 
					endfor;
				endwhile; ?>
			</ul>
		</div>
		<?php
		$stmt->close();
		}
		if ($stmt = $mysqli->prepare("SELECT labels FROM USERS")) {
		$stmt->execute();
		$stmt->bind_result($projects);
		?>
		<div class="flex-child" id="labels-list">
			<ul>
				<?php while ($stmt->fetch()) :?>
				<li><a href="?project=<?php echo $label ?>"><?php echo $label ?></a></li>
				<?php endwhile; ?>
			</ul>
		</div>
		<?php
		$stmt->close();
		}
		?>		
	</div> <?php
	/* 
	End of menu part
	Start of  the task listing */
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