<?php
	if (count(get_included_files()) <= 1) {
		exit;
	}
?>
<a class="btn btn-primary btn-block btn-md" href="newpost.php" style="margin-bottom: 10px;">Start a Discussion</a>

<div class="panel panel-default" style="border:0;border-radius:0;">
	<div class="list-group" style="border-radius:0;">
	<?php
		$catCount = count($db->getCategories());
		echo '<a class="list-group-item" href="index.php"><i class="fa fa-th-large"></i> Show All</a>';
		
		for($i = 0; $i < $catCount; $i++) {
			
			$catTitle = $db->getCategories()[$i];
			if (getPage() == "index.php") {
				echo '<a style="border-radius:0;" class="list-group-item '.($category == $i ? "active" : "").'" href="index.php?show='.$i.'">
				<i class="fa fa-tag"></i> '.$catTitle.'</a>';
			} else {
				echo '<a style="border-radius:0;" class="list-group-item" href="index.php?show='.$i.'">
				<i class="fa fa-tag"></i> '.$catTitle.'</a>';
			}
		}
	?>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">Global Statistics</div>
	<ul class="list-group">
		<li class="list-group-item">
	 		<span class="badge"><?php echo number_format($db->getUserCount()); ?></span>
			Users:
		</li>
		<li class="list-group-item">
	 		<span class="badge"><?php echo number_format($db->countTopics()); ?></span>
			Topics:
		</li>
		<li class="list-group-item">
	 		<span class="badge"><?php echo number_format($db->countReplies()); ?></span>
			Posts:
		</li>
	</ul>
</div>