<?php
if($Page->user->isLoggedIn()){
	echo 'Welcome!';
	
	?>
	<a href="?logout">Log out</a>
	<?php
}
?>