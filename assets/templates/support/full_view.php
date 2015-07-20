<?php
	if (count(get_included_files()) <= 1) {
		exit;
	}
?>
<div class="row">
	<div class="col-md-12">
		<?php include 'pagination.php'; ?>
		<h3 style="margin-top:0;"><?php echo ($category == -1 ? "All Categories" : $db->getCategories()[$category]); ?></h3>
	</div>
</div>

<hr style="margin-top:0;">

<div class="list-group">
<?php

	$min = $page == 1 ? 0 : ($page * 20) - 20;
	$result = $db->getAllPosts($min, 20, $category, $sort);

	foreach ($result as $topic) {
		$title = $topic['title'];

		if (strlen($title) > 30)
			$title = substr($title, 0, 30).'...';

		$icon = $topic['state'] == 0 ? "glyphicon glyphicon-lock" : "glyphicon glyphicon-comment";
		$likes = $topic['likes'];

		if ($likes < 10000) {
			$likes = number_format($topic['likes']);
		} else if ($likes < 999999) {
			$likes = number_format($topic['likes'] / 1000, 1).'k';
		} else {
			$likes = number_format($topic['likes'] / 1000000, 1).'m';
		}
  
		$crep = $db->getTopicReplies($topic['id']);
		$userData = $db->getUserByName($topic['poster']);
		$avatar = null;

		if ($userData != null && $userData['avatar_url'] != null) {
			$avatar = '<div class="img-circle-sm pull-left hidden-xs"><img src="'.$userData['avatar_url'].'"></div>';
		} else {
			$avatar = '<div class="img-circle-sm pull-left hidden-xs"><img src="assets/img/default.png"></div>';
		}

		$state = "";
		if ($topic['state'] == 0) {
			$state = "<i class='fa fa-lock'></i> ";
		}

		echo '
		<a class="list-group-item" href="topic.php?id='.$topic['id'].'">
			'.$avatar.'
			<h4 class="pull-right text-success text-center topic-replies hidden-sm hidden-xs">'.$likes.'<br><small>Likes</small></h4>
			<h4 class="pull-right topic-replies text-center hidden-sm hidden-xs">'.$crep.'<br><small>Replies</small></h4>
			<h4 class="list-group-item-heading topic-title">'.$state.ucwords($title).'</h4>
			<p class="list-group-item-text">
				<span class="label label-primary">'.$db->getCategories()[$topic['category']].'</span>
				Posted by <span class="text-primary">'.ucwords($userData == null ? "-Unknown-" : $userData['username']).'
			</p>
		</a>';
	}
?>
</div>
<form action="index.php">
	<div class="form-group pull-right" style="width:200px;">
		<div class="input-group">
			<select name="sort" class="form-control input-sm">
				<option value="latest">Latest Reply</option>
				<option value="liked">Most Liked</option>
			</select>
			<span class="input-group-btn">
				<button class="btn btn-default btn-sm" type="submit">Save</button>
			</span>
		</div>
	</div>
</form>