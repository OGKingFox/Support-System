<?php
	session_start();
	include 'assets/settings.php';

	if (isset($_SESSION['user'])) {
		header("location: index.php");
		exit;
	}

	$error = "";

	if (isset($_POST['username']) && isset($_POST['password'])) {
		$user = $db->checkName($_POST['username']);
		if ($user == null) {
			$error = "That user does not exist.";
		} else if ($user['banned'] == 1) {
			$error = "<i class='fa fa-warning'></i> Access Denied";
		} else {
			$decrypt = $db->decrypt($user['password'], enc_key);
			if (rtrim($decrypt) == $_POST['password']) {
				$_SESSION['user'] = $user;
				header("location: index.php");
				exit;
			} else {
				$error = "Invalid username or password";
			}
		}
	}

?>
<!DOCTYPE html>
<html>
	<?php include 'assets/templates/global/head.php'; ?>
<body>
	
		<div class="container" style="width:300px;">
			<div class="col-md-12 col-md-12">
				<h1 class="text-primary text-center">Sign In</h1>
			</div>
			<div class="col-md-12 col-sm-12">
				<form action="login.php" method="post" autocomplete="off">
					<?php
					if ($error != "")
						echo '<p class="text-danger">'.$error.'</p>';
					
					?>
					<div class="form-group">
					   	<input class="form-control" type="text" name="username" placeholder="Username">
					</div>

					<div class="form-group">
						<input class="form-control" type="password" name="password" placeholder="Password">
						<a class="text-left" href="forgot.php">Forgot Password?</a>
					</div>

					<div class="form-group">
						<button class="btn btn-primary btn-block" type="submit">Sign In</button>
						<a class="btn btn-default btn-block" href="register.php">Register</a>
					</div>
				</form>
			</div>
		</div>

	<?php include 'assets/templates/global/scripts.php'; ?>
</body>
</html>