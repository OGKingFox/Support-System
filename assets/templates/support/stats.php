<?php
	if (count(get_included_files()) <= 1) {
		exit;
	}
?>

<div class="panel panel-default">
	<table class="table table-hover">
		<tr>
			<td style="color:#FFF;background-color:rgba(0,0,0,0.2)">Support Statistics</td>
			<td style="width:150px;"><?php echo $db->getState(0); ?></td>
			<td style="width:150px;"><?php echo $db->getState(1); ?></td>
			<td style="width:150px;"><?php echo $db->getState(2); ?></td>
			<td style="width:150px;"><?php echo $db->getState(3); ?></td>
		</tr>
		<?php
			for ($i = 0; $i < count($db->getCategories()) - 1; $i++) {
				echo '
				<tr>
					<td>'.$db->getCategories()[$i].'</td>
					<td>'.number_format($db->countPosts($i, 0)).'</td>
					<td>'.number_format($db->countPosts($i, 1)).'</td>
					<td>'.number_format($db->countPosts($i, 2)).'</td>
					<td>'.number_format($db->countPosts($i, 3)).'</td>
				</tr>';
			}
		?>
	</table>
</div>