<?php
$GlobalPage->includeFile('header.php');

if($Page->user->isLoggedIn()){
	echo 'Welcome!';
	
	?>
	
		<div class="row">    
			<div class="large-9 push-3 columns">
				<h3>Page Title <small>Page subtitle</small></h3>
				<p>Bacon ipsum dolor sit amet salami ham hock biltong ball tip drumstick sirloin pancetta meatball short loin. Venison tail chuck pork chop, andouille ball tip beef ribs flank boudin bacon. Salami andouille pork belly short ribs flank cow. Salami sirloin turkey kielbasa. Sausage venison pork loin leberkas chuck short loin, cow ham prosciutto pastrami jowl. Ham hock jerky tri-tip, fatback hamburger shoulder swine pancetta ground round. Tri-tip prosciutto meatball turkey, brisket spare ribs shankle chuck cow chicken ham hock boudin meatloaf jowl.</p>
				<p></p>
			</div>


			<div class="large-3 pull-9 columns">
				<ul class="side-nav">
					<li><a href="#">New Blog Post</a></li>
					<li><a href="?logout">Log out</a></li>
					<!-- <li><a href="passwords.txt">Manage users</a></li> -->
				</ul>

				<p><img src="http://placehold.it/320x240&text=Ad"/></p>
			</div>
		</div>
	
	
	<?php
}

$GlobalPage->includeFile('footer.php');
?>