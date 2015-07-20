<?php
	if (count(get_included_files()) <= 1) {
		exit;
	}
?>

<div class="col-xs-3">
	<div class="panel panel-default">
		<div class="panel-heading">Theme Select</div>
		<div class="panel-body">
			<form action="account.php" method="post">
				<div class="form-group">
					<select class="form-control" name="theme">
						<?php
							echo '<option value="'.$user['theme'].'">'.ucwords($user['theme']).'</option><br>';
							$css_array = glob('assets/css/theme/*.css');
					        for ($i = 0; $i < count($css_array); $i++) {
					        	$file = basename($css_array[$i], ".css");
					        	if ($file == $user['theme'])
					        		continue;
					            echo '<option value="'.$file.'">'.ucwords($file).'</option><br>';
					        }
						?>
			        </select>
		        </div>
		        <div class="form-group">
						<button type="submit" class="btn btn-primary btn-block">Set Theme</button>
				</div>
			</form>	
		</div>
	</div>
</div>