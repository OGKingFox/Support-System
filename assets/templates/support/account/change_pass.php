<?php
	if (count(get_included_files()) <= 1) {
		exit;
	}
?>

<div class="col-md-4">
	<div class="panel panel-default">
		<div class="panel-heading">Change Password</div>
		<div class="panel-body">
			<form action="account.php" method="post">
				<div class="form-group">
					<input type="password" name="oldpass" class="form-control" placeholder="Current Password">
				</div>
				<div class="form-group">
					<input type="password" name="newpass" class="form-control" placeholder="New Password">
				</div>
				<div class="form-group">
					<input type="password" name="reppass" class="form-control" placeholder="Repeat Password">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block">Submit</button>
				</div>
			</form>	
		</div>
	</div>
</div>
