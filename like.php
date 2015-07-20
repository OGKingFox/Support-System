<?php

	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		echo 'Invalid 1';
		exit;
	}

	if (!isset($_POST['topic']) || !isset($_POST['type']) || !isset($_POST['username'])) {
		echo 'Invalid 2';
		exit;
	}

	if (!is_numeric($_POST['topic'])) {
		echo 'Invalid 3';
		exit;
	}

	if ($_POST['type'] != "dislike" && $_POST['type'] != 'like') {
		echo 'Invalid 4';
		exit;
	}

	include 'assets/settings.php';

	$topic = $_POST['topic'];
	$topicData = $db->getPost($topic);
	
	if ($topicData == null) {
		echo '<p class="text-danger" style="margin:0;padding:0;font-weight:bold;"><i class="fa fa-times"></i> Invalid topic specified!</p>';
		exit;
	}

	$type = $_POST['type'];
	$username = $_POST['username'];

	if ($db->getState($topicData['state']) == "Closed" || $db->getState($topicData['state']) == "Solved") {
		echo '<p class="text-danger" style="margin:0;padding:0;font-weight:bold;"><i class="fa fa-times"></i> This post can no longer be voted on</p>';
		exit;
	}

	if ($db->hasLiked($topic, $username) || $db->hasDisliked($topic, $username)) {
		echo '<div class="alert alert-danger"><i class="fa fa-times"></i> You\'ve already voted on this post!</div>';
	} else {
		if ($type == "like") {
			$db->addLike($topic, $username);
			echo '<div class="alert alert-success"><i class="fa fa-check"></i> Your like has been recorded, thank you!</div>';
		} else {
			$db->addDislike($topic, $username);
			echo '<div class="alert alert-success"><i class="fa fa-check"></i> Your dislike has been recorded, thank you!</div>';
		}
	}

?>

