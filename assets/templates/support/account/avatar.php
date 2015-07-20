<?php
	if (count(get_included_files()) <= 1) {
		exit;
	}
?>

<div class="col-xs-4">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="pull-right text-right">
				<i class="fa fa-info-circle" data-toggle="tooltip" title="Avatars large than 75x75 will be scaled down. (PNG and JPEG only!)"></i>
			</div>
			Avatar URL
		</div>
		<div class="panel-body">
			<form action="account.php" method="post">
				<div class="form-group">
					<input type="text" name="avatar" class="form-control" placeholder="Avatar URL" value="<?php echo ($curAvatar == null ? "no avatar" : $curAvatar); ?>">
				</div>
				<div class="form-group">
						<button type="submit" class="btn btn-primary btn-block">Set Avatar</button>
				</div>
			</form>	
		</div>
	</div>
</div>