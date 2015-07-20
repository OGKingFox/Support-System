<?php
	if (count(get_included_files()) <= 1) {
		exit;
	}

	if (!isset($_SESSION['user']) || $_SESSION['user'] == null) {
		header("location: login.php");
		exit;
	}
	
	$user = $db->getUser($_SESSION['user']['username'], $_SESSION['user']['password']);
	
	if ($user == null || $user['banned'] == 1 || (isset($_GET['action']) && $_GET['action'] == "logout")) {
		session_destroy();
		header("location: login.php");
		exit;
	}

	$rights = $user['rights'];
?>