<div class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php"><i class="fa fa-home"></i></a>
		</div>
		<div class="navbar-collapse collapse navbar-responsive-collapse">
			<ul class="nav navbar-nav">
				<li><a href="#">Link 1</a></li>
				<li><a href="#">Link 2</a></li></li>
				<li><a href="#">Link 3</a></li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Drop-Down <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="#">Link 1</a></li>
						<li><a href="#">Link 2</a></li></li>
						<li><a href="#">Link 3</a></li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-user"></i> <?php echo ucwords(htmlentities($_SESSION['user']['username'])); ?> <b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li><a href="account.php"><i class="fa fa-cog"></i> Settings</a></li>
						<li class="divider"></li>
						<li><a href="index.php?action=logout"><i class="fa fa-sign-out"></i> Sign Out</a></li>
					</ul>
				</li>
				<?php
					if ($_SESSION['user']['rights'] == 2) {
						echo '<li><a href="admin.php"><i class="fa fa-cog fa-lg"></i> Admin</a></li>';
					}
				?>
			</ul>
		</div>
	</div>
</div>

<?php include 'assets/templates/global/page-head.php'; ?>