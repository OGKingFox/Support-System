<?php
	session_start();
	include 'assets/settings.php';
	include 'user.php';
	
	include 'assets/classes/mail/PHPMailerAutoload.php';
	include 'assets/classes/mail/class.mail.php';

	$mailer = new Mail(new PHPMailer);
	$mailer->setSender("Foxtrot Studios");
	$mailer->setApiUser(api_user);
	$mailer->setApiPass(api_pass);
	$mailer->setFromEmail(from_email);

	$error = null;
	$success = null;

	if (isset($_POST['oldpass']) && isset($_POST['newpass']) && isset($_POST['reppass'])) {
		$curpass = $db->decrypt($_SESSION['user']['password'], enc_key);

		$oldpass = $_POST['oldpass'];
		$newpass = $_POST['newpass'];
		$reppass = $_POST['reppass'];

		if (rtrim($curpass) == $oldpass) {
			if ($newpass != $reppass) {
				$error = "The password's you have entered do not match. Please try again.";
			} else if (strlen($newpass) < 3 || strlen($newpass) > 15) {
				$error = "Password must be between 3 and 15 characters!";
			} else {
				$new = $db->encrypt($_POST['newpass'], enc_key);
				$db->setPassword($_SESSION['user']['username'], $new);
				
				$_SESSION['user']['password'] = $new;
				$mailer->setReceiver($_SESSION['user']['email']);
				$mailer->setSubject("Password Change");
				$mailer->setMessage("You've requested a password change. Your new password is as follows:<br><br>Your password: <b>".$newpass."</b>");
				
				if ($mailer->sendMail()) {
					$success = 'Your password has been set and emailed to you!';
				} else {
					$success = 'Your password has been set, but we were unable to send an email. Your new password: <b>'.$newpass.'</b>!';
				}
			}
		} else {
			$error = "Please check your current password and try again. '".$curpass."'";
		}

	}

	if (isset($_POST['avatar'])) {
		$url = filter_var($_POST['avatar'], FILTER_SANITIZE_URL);
		if (empty($_POST['avatar'])) {
			$db->setAvatarUrl($_SESSION['user']['username'], null);
			$_SESSION['user']['avatar_url'] = null;
			$success = "Your avatar url has been removed!";
		} else {
			$filtered = filter_var($url, FILTER_VALIDATE_URL);
			$ext = pathinfo($url, PATHINFO_EXTENSION);
			
			if ($filtered && ($ext == "jpeg" || $ext == "png")) {
				$_SESSION['user']['avatar_url'] = $url;
				$db->setAvatarUrl($_SESSION['user']['username'], htmlentities($url));
				$success = "Your avatar url has been set!";
			} else {
				$error = "Please specify a valid url.";
			}
		}
	}
	
	if (isset($_POST['email'])) {
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$error = "Please check the email address you entered and try again.";
		} else {
			$email = $db->checkEmail($_POST['email']);
			if ($email != null) {
				$error = "That email is aleady in use. Please try another.";
			} else {
				$db->setEmailAddress($_SESSION['user']['username'], htmlentities($_POST['email']));
				$_SESSION['user']['email'] = $_POST['email'];
				$success = "Your email address has been updated!";
			}
		}
	}

	$curAvatar = $_SESSION['user']['avatar_url'];
?>

<!DOCTYPE html>
<html>
<?php include 'assets/templates/global/head.php'; ?>
<body>

	<?php include 'assets/templates/global/navigation.php';?>

	<header class="jumbotron">
		<div class="container">
			<h2>Foxtrot Support</h2>
			<div class="subtitle">Account Settings</div>
		</div>
	</header>

	<div class="container">
		<div class="col-xs-12">
			<?php
				if ($error != null) {
					echo '<div class="alert alert-danger"><i class="fa fa-times"></i> '.$error.'</div>';
				} else if ($success != null) {
					echo '<div class="alert alert-success"><i class="fa fa-check"></i> '.$success.'</div>';
				}
			?>
		</div>

		<div class="col-md-3"><?php include 'assets/templates/support/sidebar.php'; ?></div>
		<div class="col-md-9">
		<?php
			include 'assets/templates/support/account/avatar.php';
			include 'assets/templates/support/account/email.php'; 
			include 'assets/templates/support/account/change_pass.php'; 
		?>
		</div>
	</div>
	<?php include 'assets/templates/global/footer.php'; ?>

</body>
<?php include 'assets/templates/global/scripts.php'; ?>
</html>