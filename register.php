<?php
	session_start();
	include 'assets/settings.php';

	if (isset($_SESSION['user'])) {
		header("location: index.php");
		exit;
	}

	$error = "";
	$success = "";

	if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
		
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		   	$error = "Invalid email address";
		} else if(!is_string($_POST['username']) || !preg_match('/^[a-zA-Z0-9 ]+$/', $_POST['username'])) {
			$error = "Name must be numbers, letters, and spaces only!";
		} else if (strlen($_POST['username']) < 3 || strlen($_POST['username']) > 15) {
			$error = "Name must be 3 to 15 characters";
		} else if (strlen($_POST['password']) < 3 || strlen($_POST['password']) > 15) {
			$error = "Pass must be 3 to 15 characters";
		}

		if ($error == "") {
			$user = $db->checkName($_POST['username']);
			$email = $db->checkEmail($_POST['email']);

			if ($user != null) {
				$error = "A user with this name already exists.";
			} else if ($email != null) {
				$error = "This email address is already in use.";
			} else {
				$encrypted = $db->encrypt($_POST['password'], enc_key);
				if ($db->registerUser($_POST['username'], rtrim($encrypted), $_POST['email'])) {
					$success = "Success! You may now log in.";
				} else {
					$error = "Error processing request. Try again.";
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html>
	<?php include 'assets/templates/global/head.php'; ?>
<body>
	
	<div class="container text-center" style="width:300px;">
		<div class="col-md-12 col-md-12">
			<h1 class="text-primary text-center">Register</h1>
		</div>
		<div class="col-md-12 col-sm-12">
			<form action="register.php" method="post" autocomplete="off">
				<?php
					if ($error != "")
						echo '<p class="text-danger">'.$error.'</p>';
					if ($success != "")
						echo '<p class="text-success">'.$success.'</p>';
				?>
				<div class="form-group">
				   	<input class="form-control" type="email" name="email" placeholder="Email Address">
				</div>

				<div class="form-group">
				   	<input class="form-control" type="text" name="username" placeholder="Username">
				</div>

				<div class="form-group">
					<input class="form-control" type="password" name="password" placeholder="Password">
				</div>

				<div class="form-group">
					<button class="btn btn-primary btn-block" type="submit">Register</button>
					<a class="btn btn-default btn-block" href="login.php">Sign In</a>
				</div>
			</form>
		</div>
	</div>

	<?php include 'assets/templates/global/scripts.php'; ?>
</body>
</html>