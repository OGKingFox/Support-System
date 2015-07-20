<?php
	session_start();
	include 'assets/settings.php';
	include 'assets/classes/mail/PHPMailerAutoload.php';
	include 'assets/classes/mail/class.mail.php';

	$mailer = new Mail(new PHPMailer);
	$mailer->setSender("Foxtrot Studios");
	$mailer->setApiUser(api_user);
	$mailer->setApiPass(api_pass);
	$mailer->setFromEmail(from_email);
	
	if (isset($_SESSION['user'])) {
		header("location: index.php");
		exit;
	}

	$error = "";
	$success = "";

	if (isset($_POST['email'])) {
		$email = $_POST['email'];
		
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		   	$error = "Invalid email address";
		} else {
			$valid = $db->checkEmail($email);
			if ($valid == null) {
				$error = "That email does not exist";
			} else {
				$success = "Your password has been emailed to you.";
				$pass = $db->decrypt($valid['password'], enc_key);
				
				$mailer->setReceiver($valid['email']);
				$mailer->setSubject("Password Recovery");
				
				$message = "You have requested your password, it is below:<br><br>";
				$message .= "Username: <b>".$valid['username']."</b><br>";
				$message .= "Password: <b>".$pass."</b><br>";
				
				$mailer->setMessage($message);
				$mailer->sendMail();
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
			<h1 class="text-primary text-center">Recover</h1>
		</div>
		<div class="col-md-12 col-sm-12">
			<form action="forgot.php" method="post" autocomplete="off">
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
					<button class="btn btn-primary btn-block" type="submit">Send Email</button>
					<a class="btn btn-default btn-block" href="login.php">Sign In</a>
				</div>

			</form>
		</div>
	</div>

	<?php include 'assets/templates/global/scripts.php'; ?>
</body>
</html>