<?php
	if (count(get_included_files()) <= 1) {
		exit;
	}

	$title = $news['title'];

	if (strlen($title) > 30)
		$title = substr($title, 0, 30).'...';

	
	echo '<tr>';
	echo '<td class="text-right"><span class="label label-primary">'.$db->getCategories()[$news['category']].'</span></td>';
	echo '<td><a href="topic.php?id='.$news['id'].'">'.$title.'</a></td>';
	echo '<td class="text-center">'.ucwords($news['poster']).'</td>';
	echo '<td class="text-center"><i class="fa fa-comments"></i> '.number_format($db->getTopicReplies($news['id'])).'</td>';


	if ($news['state'] < 2) {
		echo '
			<td style="width:80px;text-align:center;">
				<a id="like"  data-likes="'.$news['likes'].'" data-topic="'.$news['id'].'" data-user="'.$_SESSION['user']['username'].'" class="text-success" href="#">
					<i class="fa fa-thumbs-o-up"></i> '.$news['likes'].'
				</a>
			</td>';
		echo '
			<td style="width:80px;text-align:center;">
				<a id="dislike" data-dislikes="'.$news['dislikes'].'" data-topic="'.$news['id'].'" data-user="'.$_SESSION['user']['username'].'" class="text-danger" href="#">
					<i class="fa fa-thumbs-o-down"></i> '.$news['dislikes'].'
				</a>
			</td>';
	} else {
		echo '<td style="width:80px;text-align:center;">---</td>';
		echo '<td style="width:80px;text-align:center;">---</td>';
	}
	
	echo '</tr>';
?>