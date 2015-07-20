<?php
	if (count(get_included_files()) <=1) {
		exit;
	}
?>
<form action="edit.php" method="POST">
	<div class="form-group">
		<textarea name="body" class="form-control" style="min-height:300px;" value=""><?php echo $purifier->purify($topic['body']); ?></textarea>
	</div>

	<input type="hidden" value="<?php echo $topic['id']; ?>" name="id">

	<div class="form-group text-right">
		<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
	</div>
</form>