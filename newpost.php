<?php
	session_start();
	include 'assets/settings.php';
	include 'user.php';

	$title = null;
	$body = null;
	$error = null;

	if (isset($_POST['title']) && isset($_POST['body'])) {
		$title = strval($_POST['title']);
		$body = $purifier->purify($_POST['body']);
		$cat = $_POST['category'];

		if (strlen($title) < 5 || strlen($title) > 30) {
			$error = '<div class="alert alert-danger">Topic title must be between 3 and 30 characters.</div>';
		} else if (strlen($body) < 10 || strlen($body) > 50000) {
			$error = '<div class="alert alert-danger">Topic body must be between 10 and 50,000 characters.</div>';
		} else if (!is_numeric($cat) || $cat < 0 || $cat > count($db->getCategories()) - 1) {
			$error = '<div class="alert alert-danger">Invalid Category Selection</div>';
		}  else {
			if ($db->createPost($user['username'], $cat, $title, $body)) {
				header("location: index.php");
				exit;
			}
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
				<div class="subtitle">Start new Discussion</div>
			</div>
		</header>

		<div class="container">
			<div class="col-xs-3">
				<?php include 'assets/templates/support/sidebar.php'; ?>
			</div>

			<div class="col-xs-9">
				<?php
					if ($error != null) {
						echo $error;
					}
				?>
				<div class="well">
					<form action="newpost.php" method="post" novalidate>
						<div class="form-group input-group">
							<div class="input-group-addon" style="min-width:100px;padding:0;border:none;">
								<select class="form-control" id="select" name="category">
									<?php
										for ($i = 0; $i < count($db->getCategories()); $i++) {
											echo '<option value="'.$i.'">'.$db->getCategories()[$i].'</option>';
										}
									?>
							    </select>
							</div>
							<input type="text" class="form-control" name="title" maxlength="30" placeholder="Title" value="<?php echo ($title == null ? "" : $title); ?>" required>
						</div>
						<div class="form-group">
							<textarea name="body" class="form-control" style="min-height:300px;max-width:100%;" required><?php echo ($body == null ? "" : $body); ?></textarea>
						</div>
						<div class="form-group text-right">
							<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Create</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php include 'assets/templates/global/footer.php'; ?>
	</body>
	<?php include 'assets/templates/global/scripts.php'; ?>
</html>