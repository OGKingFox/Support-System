<?php
	session_start();
	include 'assets/settings.php';
	include 'user.php';

	if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
		header("location: index.php");
		exit;
	}

	$topicId = intval($_GET['id']);
	$error = null;
	$topic = null;
	$page = 1;
	$postError = null;

	if (isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page'])) {
		$page = $_GET['page'];
	}

	if ($topicId < 0) {
		$error = "Invalid topic id specified.";
	} else {
		$topic = $db->getPost($topicId);

		if (isset($_GET['delete'])) { 
			if($_GET['delete'] == "topic") {
				if ($rights >= 1 || $topic['author'] == strtolower($user['username'])) {
					$db->deleteTopicAndReplies($topicId);
					header("location: index.php");
					exit;
				} else {
					$error = "You do not have permission to delete this post!";
				}
			} else if ($_GET['delete'] == "reply") {
				$reply = $db->getReply($topicId);

				if ($rights >= 1 || $reply['author'] == strtolower($user['username'])) {
					$db->deleteReply($topicId);
					header("location: topic.php?id=".$reply['topic'].($page > 1 ? "&page=".$page."" : "")."");
					exit;
				} else {
					$error = "You do not have permission to delete this post!";
				}
			}
		}

		if (isset($_GET['status'])) {
			if ($rights >= 1) {
				if ($db->setPostStatus($topicId, intval($_GET['status']))) {
					header("location: topic.php?id=".$topicId."");
					exit;
				}
			}
		}

		if (isset($_POST['rep_body'])) {
			if ($topic['state'] == 0) {
				$postError = "<div class='alert alert-danger'>Unable to post reply. Please try again.</div>";
			} else {
				$repBody = $_POST['rep_body'];
				$repBody = $purifier->purify($repBody);

				if (strlen($_POST['rep_body']) < 5) {
					$postError = "<div class='alert alert-danger'>Your reply must be atleast 5 characters long.</div>";
				} else if (strlen($_POST['rep_body']) > 50000) {
					$postError = "<div class='alert alert-danger'>Your reply must be no longer than 50,000 characters.</div>";
				} else {
					if (!$db->addReply($topicId, $user['username'], $repBody)) {
						$postError = "<div class='alert alert-danger'>Unable to post reply. Please try again.</div>";
					} else {
						header("location: topic.php?id=".$topicId."");
						exit;
					}
				}
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
			<div class="subtitle">
			<?php 
				if ($topic != null) {
					echo ''.strip_tags(ucwords($topic['title'])).''; 
				}
			?>
			</div>
		</div>
	</header>

	<div class="container">
		<div class="col-md-12"><div id="like-alert"></div></div>

		<div class="col-md-3">
			<?php include 'assets/templates/support/sidebar.php'; ?>
		</div>

		<div class="col-md-9">
			<div class="col-md-12" id="like-alert"></div>

			<?php
				if ($topic == null || $error != null) {
					if ($topic == null)
						echo '<div class="alert alert-danger">The topic you\'re looking for does not exist.</div>';
					else
						echo '<div class="alert alert-danger">'.$error.'</div>';
				} else {
					$userData = $db->getUserByName($topic['poster']);
					echo '<a href="#" id="dislike" data-topic="'.$topicId.'" data-user="'.$user['username'].'" class="btn btn-danger btn-sm pull-right">
							<i class="fa fa-thumbs-o-down"></i> Dislike
						</a> ';
					echo '<a href="#" id="like" data-topic="'.$topicId.'" data-user="'.$user['username'].'" class="btn btn-success btn-sm pull-right" style="margin-right:5px;">
							<i class="fa fa-thumbs-o-up"></i> Like
						</a> ';

					echo '<div class="header-block"><h3><small>Posted by '.($userData == null ? "Unknown" : $userData['username']).'</small></h3></div>';
					
					echo '<hr>';

					if ($page == 1) {
						$date = date("F j, Y g:i a", strtotime($topic['date']));
						$avatar = '<div class="img-circle"><img src="assets/img/default.png"></div>';

						$rank = '';

						if ($userData != null) {
							if ($userData['rights'] == 1) {
								$rank = '<div class="label label-primary">Moderator</div>';
							} else if ($userData['rights'] == 2) {
								$rank = '<div class="label label-primary">Administrator</div>';
							}
							if ($userData['avatar_url'] != null) {
								$avatar = '<div class="img-circle"><img src="'.$userData['avatar_url'].'"></div>';
							}
						}

						$body = nl2br_special($topic['body']);
						$body = $purifier->purify($body);

						echo '
						<div class="col-md-2 text-center post-info">
							'.$avatar.'
							'.$rank.'
						</div>


						<div class="col-md-10 post-body">
							<strong>'.ucwords($userData == null ? "Unknown" : $userData['username']).'</strong> - '.$date.'<br><br>
							'.$purifier->purify($body).'';

						echo '<div class="post-btns">';

						if ($userData != null) {
							if ($rights >= 1 || strtolower($user['username']) == strtolower($userData['username'])) {
								echo '<a href="edit.php?id='.$topicId.'" class="btn btn-warning btn-xs"><i class="fa fa-pencil-square-o"></i> Edit</a> ';
							}
						}

						if ($rights >= 1) {
							echo '<a href="?id='.$topicId.'&delete=topic" class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Delete</a> ';

							if ($topic['state'] == 1) {
								echo '<a href="?id='.$topicId.'&status=0" class="btn btn-danger btn-xs"><i class="fa fa-lock"></i> Lock</a> ' ;
							} else {
								echo '<a href="?id='.$topicId.'&status=1" class="btn btn-danger btn-xs"><i class="fa fa-unlock"></i> Unlock</a>';
							}
						}

						echo '</div>';
						echo '</div>';

						echo '<div class="col-xs-12"><hr></div>';
					}

					$reps = $db->getAllReplies($topicId);

					if ($reps != null) {
						$min = $page == 1 ? 0 : ($page * 10) - 10;
						$replies = $db->getReplies($topicId, $min, 10);

						$repcount = $min;

						foreach ($replies as $reply) {
							$body = $reply['body'];
							$body = nl2br($purifier->purify($reply['body']));

							$userData = $db->getUserByName($reply['author']);

							$tidy = new tidy();
							$tidy->parseString($body, array('show-body-only'=>true),'utf8');
							$tidy->cleanRepair();

							$rank = '';

							if ($userData['rights'] == 1) {
								$rank = '<div class="label label-primary">Moderator</div>';
							} else if ($userData['rights'] == 2) {
								$rank = '<div class="label label-primary">Administrator</div>';
							}

							$date = date("F j, Y, g:i a", strtotime($reply['timePosted']));

							$avatar = '<div class="img-circle"><img src="assets/img/default.png"></div>';

							if ($userData['avatar_url'] != null) {
								$avatar = '<div class="img-circle"><img src="'.$userData['avatar_url'].'"></div>';
							}

							echo '
							<div class="col-md-2 text-center">
								'.$avatar.'
								'.$rank.'
							</div>

							<div class="col-md-10" id="'.$repcount.'">
								<div class="pull-right"><a href="#'.$repcount.'">#'.$repcount.'</a></div>
								<strong>'.ucwords($userData['username']).'</strong> - '.$date.'<br><br>
								'.$tidy.'
							';

							echo '<div class="post-btns">';

							if ($rights >= 1 || strtolower($user['username']) == strtolower($userData['username'])) {
								echo '<a href="?id='.$reply['id'].'&delete=reply" class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Delete</a>';
							}
							echo '</div></div>';
							echo '<div class="col-md-12"><hr></div>';

							$repcount++;
						}

						$maxPages = count($reps) > 10 ? ceil(count($reps) / 10) : 1;

						echo '<div class="col-md-12"><ul class="pagination">';
					
						if ($page == 1) {
							echo '<li class="disabled"><a href="#"><i class="fa fa-angle-left"></i></a></li>';
						} else {
							echo '<li><a href="?id='.$topicId.'&page='.($page - 1).'"><i class="fa fa-angle-left"></i></a></li>';
						}
						
						for ($i = 0; $i < $maxPages; $i++) {
							$pageNo = ($i + 1);
							if ($pageNo == $page) {
								echo '<li class="active"><a href="?id='.$topicId.'&page='.($i+1).'">'.$pageNo.'</a></li>';
							} else {
								echo '<li><a href="?id='.$topicId.'&page='.($i+1).'">'.$pageNo.'</a></li>';
							}
						}
						
						if ($page == $maxPages) {
							echo '<li class="disabled"><a href="#"><i class="fa fa-angle-right"></i></a></li>';
						} else {
							echo '<li><a href="?id='.$topicId.'&page='.($page + 1).'"><i class="fa fa-angle-right"></i></a></li>';
						}

						echo '</ul></div>';
					}

					if ($topic['state'] == 1) {
						include 'assets/templates/support/reply_box.php';
					} else {
						echo '
							<div class="col-md-12">
								<div class="alert alert-danger"><i class="icon icon-times"></i> This topic is closed and replies can no longer be posted.</div>
							</div>
						';
					}
				}
			?>
		</div>
	</div>
	<?php include 'assets/templates/global/footer.php'; ?>

</body>
<?php include 'assets/templates/global/scripts.php'; ?>
</html>