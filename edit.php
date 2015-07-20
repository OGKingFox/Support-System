<?php
	session_start();
	include 'assets/settings.php';
	include 'user.php';
	
	if (isset($_POST['id']) && isset($_POST['body']) && is_numeric($_POST['id'])) {
		$id = intval($_POST['id']);
		$body = $purifier->purify($_POST['body']);

		if ($db->updatePost(intval($_POST['id']), $body)) {
			header("location: topic.php?id=".$id."");
			exit;
		}
	}

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		header("location: index.php");
		exit;
	}

	$topicId = intval($_GET['id']);
	$topic = $db->getPost($topicId);

?>

<!DOCTYPE html>
<html>
<?php include 'assets/templates/global/head.php'; ?>
<body>

	<?php include 'assets/templates/global/navigation.php'; ?>

	<header class="jumbotron">
		<div class="container">
			<h2>Foxtrot Support</h2>
			<div class="subtitle">Editing Topic</div>
		</div>
	</header>

	<div class="container">
		<div class="col-md-3">
			<?php include 'assets/templates/support/sidebar.php'; ?>
		</div>
		<div class="col-md-9">
			<h3 style="margin-top:0;"><?php echo ($topic == null ? 'Invalid Topic Specified' : 'Edit Post: '.strip_tags(ucwords($topic['title'])).''); ?></h3>
			<hr>
			<?php
				if ($user['rights'] == 0) {
					if ($topic == null || strtolower($user['username']) != strtolower($topic['poster'])) {
						echo '<div class="alert alert-danger">You do not have permission to edit this post</div>';
					} else {
						include 'assets/templates/support/edit_form.php';
					}
				} else {
					if ($topic == null) {
						echo '<div class="alert alert-danger">The topic you are looking for does not exist.</div>';
					} else
						include 'assets/templates/support/edit_form.php';
				}
			?>	
		</div>
	</div>
	<?php include 'assets/templates/global/footer.php'; ?>


</body>
<?php include 'assets/templates/global/scripts.php'; ?>
</html>