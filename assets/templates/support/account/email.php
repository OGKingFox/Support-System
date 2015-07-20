<?php
	if (count(get_included_files()) <= 1) {
		exit;
	}
?>

<div class="col-md-4">
	<div class="panel panel-default">
		<div class="panel-heading">Email Address</div>
		<div class="panel-body">
			<form action="account.php" method="post">
				<div class="form-group">
					<input type="text" name="email" class="form-control" value="<?php echo $_SESSION['user']['email']; ?>">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block">Submit</button>
				</div>
			</form>	
		</div>
	</div>
</div>