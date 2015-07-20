<?php
	if (count(get_included_files()) <= 1) {
		exit;
	}

	$topics = $db->countAllTopics($category);
	$maxPages = $topics > 10 ? ceil($topics / 10) : 1;
	
	if ($maxPages > 0) {
		echo '
			<ul class="pagination pull-right" style="margin-top:0;">';
					
		if ($page == 1) {
			echo '<li class="disabled"><a href="#">Prev</a></li>';
		} else {
			if ($category == -1) {
				echo '<li><a href="?page='.($page - 1).'">Prev</a></li>';
			} else {
				echo '<li><a href="?show='.$category.'&page='.($page - 1).'">Prev</a></li>';
			}
		}
		
		if ($page == $maxPages) {
			echo '<li class="disabled"><a href="#">Next</a></li>';
		} else {
			if ($category == -1) {
				echo '<li><a href="?page='.($page + 1).'">Next</a></li>';
			} else {
				echo '<li><a href="?show='.$category.'&page='.($page + 1).'">Next</a></li>';
			}
		}

		echo '</ul>';
	}
?>


