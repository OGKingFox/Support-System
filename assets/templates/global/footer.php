<footer >
	<div class="container">
		Copyright &copy; 2015 Foxtrot Studios - Created by King Fox<br>
		<a href="#">Contact Us</a><br>
		Query Count: <?php echo $db->getQueryCount(); ?> - Generated in <?php echo (microtime(false) - $pageStart); ?>s
	</div>
</footer>