<?php
	if (count(get_included_files()) <= 1) {
		exit;
	}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<?php echo '<a href="">'.$db->getState($category).'</a>'; ?>
	</div>
	<table class="table table-hover">
		<tr>
			<td style="width:90px;font-size:12px;padding:2px;" class="text-right">Category</td>
			<td style="padding:2px;font-size:12px;" class="text-center"></td>
			<td style="width:130px;padding:2px;font-size:12px;" class="text-center">Author</td>
			<td style="width:80px;padding:2px;font-size:12px;" class="text-center">Replies</td>
			<td style="width:80px;padding:2px;font-size:12px;" class="text-center">Likes</td>
			<td style="width:80px;padding:2px;font-size:12px;" class="text-center">Dislikes</td>
		</tr>
		<?php
			$result = $db->getPostsByState($filter, $category, 100);
			foreach ($result as $news) {
				include 'assets/templates/support/post_info.php';
			}
		?>
	</table>
</div>