<?php
	session_start();
	include 'assets/settings.php';
	include 'user.php';

	$error = null;
	$success = null;

	if ($user['rights'] == 2) {

		if (isset($_GET['remove'])) {
			$ruser = $db->getUserById(cleanInt($_GET['remove']));

			if ($ruser == null) {
				$_SESSION['error'] = 'Somehow, this user does not exist.';
			} else if ($ruser['id'] == $user['id']) {
				$_SESSION['error'] = 'You are not allowed to remove yourself!';
			} else {
				$db->setRights($ruser['id'], 0);
				$_SESSION['success'] = $ruser['username'].'\'s status has been revoked.';
			}

			header("location: admin.php");
			exit;
		}

		if (isset($_POST['addadmin'])) {
			$newuser = $db->checkName(cleanString($_POST['addadmin']));

			if ($newuser == null) {
				$_SESSION['error'] = "A user by that name does not exist.";
			} else if ($newuser['rights'] == 2) {
				$_SESSION['error'] = "".$newuser['username']." already has admin!";
			} else {
				$db->setRights($newuser['id'], 2);
				$_SESSION['success'] = "".$newuser['username']." has been given admin!";
			}

			header("location: admin.php");
			exit;
		}

		if (isset($_POST['addmod'])) {
			$newuser = $db->checkName(cleanString($_POST['addmod']));

			if ($newuser == null) {
				$_SESSION['error'] = "A user by that name does not exist.";
			} else if ($newuser['rights'] == 1 || $newuser['rights'] == 2) {
				$_SESSION['error'] = $newuser['username']." already has moderator!";
			} else {
				$db->setRights($newuser['id'], 1);
				$_SESSION['success'] = $newuser['username']." has been given moderator!";
			}

			header("location: admin.php");
			exit;
		}
		
		if (isset($_POST['addban'])) {
			$newuser = $db->checkName(cleanString($_POST['addban']));
		
			if ($newuser == null) {
				$_SESSION['error'] = "A user by that name does not exist.";
			} else if ($newuser['banned'] == 1) {
				$_SESSION['error'] = $newuser['username']." is already banned!";
			} else if ($newuser['id'] == $user['id']) {
				$_SESSION['error'] = 'You are not allowed to ban yourself!';
			} else {
				$db->setBanned($newuser['id'], 1);
				$_SESSION['success'] = $newuser['username']." has been banned!";
			}
		
			header("location: admin.php");
			exit;
		}
		
		if (isset($_GET['unban'])) {
			$ruser = $db->getUserById(cleanInt($_GET['unban']));
		
			if ($ruser == null) {
				$_SESSION['error'] = 'Somehow, this user does not exist.';
			} else if ($ruser['banned'] == 0) {
				$_SESSION['error'] = $ruser['username'].' is not banned';
			} else {
				$db->setBanned($ruser['id'], 0);
				$_SESSION['success'] = $ruser['username'].' has been unbanned';
			}
		
			header("location: admin.php");
			exit;
		}
	}
	
 
?>

<!DOCTYPE html>
<html>
<?php include 'assets/templates/global/head.php'; ?>
<body>
	<?php include 'assets/templates/global/navigation.php'; ?>

	<header class="jumbotron">
		<div class="container">
			<h2>Foxtrot Support</h2>
			<div class="subtitle">Admin Panel</div>
		</div>
	</header>

	<div class="container">

		<div class="col-xs-12">
			<?php
				if (isset($_SESSION['error'])) {
					echo '<div class="alert alert-danger"><i class="fa fa-times"></i> '.$_SESSION['error'].'</div>';
					unset($_SESSION['error']);
				} else if (isset($_SESSION['success'])) {
					echo '<div class="alert alert-success"><i class="fa fa-check"></i> '.$_SESSION['success'].'</div>';
					unset($_SESSION['success']);
				}
			?>
		</div>

		<div class="col-md-3">
			<?php include 'assets/templates/support/sidebar.php'; ?>
		</div>

		<div class="col-md-9">
			<?php
				if ($user['rights'] != 2) {
					echo '<div class="col-xs-9">
					<div class="alert alert-danger"><i class="fa fa-times"></i>You do not have permission to view this page!</div>
					</div>';
				} else {
			?>

			<div class="col-xs-4">
				<div class="panel panel-default">
					<div class="panel-heading">Admins</div>
					<ul class="list-group">
						<?php
							$users = $db->getUsersByRights(2);
							foreach ($users as $usr) {
								echo '
								<li class="list-group-item">
									<span class="badge"><a href="?remove='.$usr['id'].'"><i class="fa fa-times"></i></a></span>
							    	'.$usr['username'].'
							  	</li>
								';
							}
						?>
					</ul>
					<div class="panel-footer">
						<form action="admin.php" method="post">
							<div class="input-group">
								<input type="text" class="form-control input-sm" name="addadmin" placeholder="Add Username">
								<span class="input-group-btn">
	      							<button class="btn btn-default btn-sm" type="button"><i class="fa fa-plus"></i></button>
	    						</span>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-xs-4">
				<div class="panel panel-default">
					<div class="panel-heading">Moderators</div>

					<ul class="list-group">
						<?php
							$users = $db->getUsersByRights(1);
							foreach ($users as $usr) {
								echo '
								<li class="list-group-item">
									<span class="badge"><a href="?remove='.$usr['id'].'"><i class="fa fa-times"></i></a></span>
							    	'.$usr['username'].'
							  	</li>
								';
							}
						?>
					</ul>

					<div class="panel-footer">
						<form action="admin.php" method="post">
							<div class="input-group">
								<input type="text" class="form-control input-sm" name="addmod" placeholder="Add Username">
								<span class="input-group-btn">
	      							<button class="btn btn-default btn-sm" type="button"><i class="fa fa-plus"></i></button>
	    						</span>
							</div>
						</form>
					</div>
				</div>
			</div>
			
			<div class="col-xs-4">
				<div class="panel panel-default">
					<div class="panel-heading">Banned Users</div>

					<ul class="list-group">
						<?php
							$users = $db->getBannedUsers();
							foreach ($users as $usr) {
								echo '
								<li class="list-group-item">
									<span class="badge"><a href="?unban='.$usr['id'].'"><i class="fa fa-times"></i></a></span>
							    	'.$usr['username'].'
							  	</li>
								';
							}
						?>
					</ul>

					<div class="panel-footer">
						<form action="admin.php" method="post">
							<div class="input-group">
								<input type="text" class="form-control input-sm" name="addban" placeholder="Add Username">
								<span class="input-group-btn">
	      							<button class="btn btn-default btn-sm" type="button"><i class="fa fa-plus"></i></button>
	    						</span>
							</div>
						</form>
					</div>
				</div>
			</div>
			
		<?php } ?>
		</div>
	</div>
	
	<?php include 'assets/templates/global/footer.php'; ?>
</body>
<?php include 'assets/templates/global/scripts.php'; ?>
</html>