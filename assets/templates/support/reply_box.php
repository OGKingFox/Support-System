<?php
	if (count(get_included_files()) <= 1) {
		exit;
	}
?>

 <div class="row" style="margin-bottom:30px;">
    <div class="col-md-12">
    	<?php
    		if ($postError != null) {
				echo $postError;
			}
    	?>
    	<h3>Post a Reply</h3>
    	<hr>
		<form action="topic.php?id=<?php echo $topicId; ?>" method="post" novalidate>
			<textarea name="rep_body" class="form-control reply-box" id="replybox" placeholder="Type a reply in this box, and press the send button below."></textarea>
			<div class="text-right">
				<button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Post Reply</button>
			</div>
		</form>
	</div>
</div>