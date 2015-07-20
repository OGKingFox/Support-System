<?php
	session_start();
	include 'assets/settings.php';
	include 'user.php';

	$categories = $db->getCategories();
	$category = isset($_GET['show']) ? cleanInt($_GET['show']) : -1;

	if (!$db->isValidCategory($category))
		$category = -1;

	$page = isset($_GET['page']) && is_numeric($_GET['page']) ? cleanInt($_GET['page']) : 1;
	$sort = isset($_SESSION['sort']) ? $_SESSION['sort'] : "last_post";

	if (isset($_GET['sort']) && ($_GET['sort'] == "latest" || $_GET['sort'] == "liked")) {
		$_SESSION['sort'] = $_GET['sort'] == "latest" ? "last_post" : "likes";
		header("location:index.php?".($category == -1 ? "" : "show=".$category."&")."page=".$page);
		exit;
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
			<div class="subtitle">
			<?php 
				if ($category == -1) {
					echo 'Showing: <div class="label label-primary">Everything</div>'; 
				} else {
					echo 'Showing: <div class="label label-primary">'.$categories[$category].'</div>'; 
				}
			?>
			</div>
		</div>
	</header>

	<div class="container">
		<div class="col-md-12" id="like-alert"></div>

		<div class="col-md-3">
			<?php include 'assets/templates/support/sidebar.php'; ?>
		</div>

		<div class="col-md-9">
			<?php include 'assets/templates/support/full_view.php'; ?>
		</div>

	</div>

	<?php include 'assets/templates/global/footer.php'; ?>
</body>
<?php include 'assets/templates/global/scripts.php'; ?>
</html>