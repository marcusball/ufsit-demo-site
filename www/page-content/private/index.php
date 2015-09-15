<?php
$GlobalPage->includeFile('header.php');

if($Page->user->isLoggedIn()){
	?>
	
		<div class="row">    
			<div class="large-9 push-3 columns">
				<h3>You got it!</h3>
				<h3><small><?php
				if($this->user->getUid() == 'guest'){
					echo 'You got the guest account! Good job, now see if you can find the others!';
				}
				elseif($this->user->getUid() == 'bender' || $this->user->getUid() == 'winnfield'){
					echo 'You got the user account of ' . $this->user->getUid() . '! Great work! Can you get the admin account?';
				}
				elseif($this->user->getUid() == 'admin'){
					echo 'You\'ve owned us! You have the admin account and now possess full control!';
				}
				else{
					echo 'I have no idea what is going on.';
				}
				?></small>
				</h3>
				<?php if($this->user->getUid() == 'admin'){ ?>
				<img src="/res/images/8a5.png" />
				<?php } ?>
				<p>Bacon ipsum dolor sit amet salami ham hock biltong ball tip drumstick sirloin pancetta meatball short loin. Venison tail chuck pork chop, andouille ball tip beef ribs flank boudin bacon. Salami andouille pork belly short ribs flank cow. Salami sirloin turkey kielbasa. Sausage venison pork loin leberkas chuck short loin, cow ham prosciutto pastrami jowl. Ham hock jerky tri-tip, fatback hamburger shoulder swine pancetta ground round. Tri-tip prosciutto meatball turkey, brisket spare ribs shankle chuck cow chicken ham hock boudin meatloaf jowl.</p>
				<p></p>
			</div>


			<div class="large-3 pull-9 columns">
				<ul class="side-nav">
					<li><a href="#">New Blog Post</a></li>
					<li><a href="?logout">Log out</a></li>
					<li><a href="passwords.txt">Manage users</a></li>
				</ul>
				<?php if($this->user->getUid() == 'guest'){ ?>
				<p><img src="/res/images/1.jpg" /></p>
				<?php } elseif($this->user->getUid() == 'winnfield'){ ?>
				<p><img src="/res/images/2.jpg" /></p>
				<?php } elseif($this->user->getUid() == 'bender'){ ?>
				<p><img src="/res/images/3.jpg" /></p>
				<?php } elseif($this->user->getUid() == 'admin'){ ?>
				<p><img src="/res/images/well-done.png" /></p>
				<?php } ?>
			</div>
		</div>
	
	
	<?php
}

$GlobalPage->includeFile('footer.php');
?>