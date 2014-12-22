<?php
	/*
	 *  File which holds menu
	 */
	 /* Start menu */
?>	
<div class="flex-parent main"> 	
	<div class="flex-child flex-parent flex-column" id="meta-info">
		<h1 class="flex-child">To Do Lister</h1>
		<div class="flex-child time-list" id="time-list">
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
					for ($i = 0; $i < count($projectArr); $i++):
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
				<li><a href="?label=<?php echo $label ?>"><?php echo $label ?></a></li>
				<?php endwhile; ?>
			</ul>
		</div>
		<?php
		$stmt->close();
		}
		?>		
		<a href="<?php echo TDL_PATH ?>tdl-logout.php">Log out</a>
	</div> <?php
	/* End of menu part */
	?>