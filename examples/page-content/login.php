<?php $GlobalPage->includeFile('header.php'); ?>
				<section class="ca">
					<div class="box">
						<h2>Wow, such login.</h2>
						
						<?php if(!$Page->loginSuccessful()){ /* If there were errors registering, or if no registration attempt has been made */ ?>
							<?php if($this->response->hasErrors()){ /* If there were errors listed on the page, let's output them now. */ ?>
								<div id="errorsContainer">
								<?php $this->response->outputErrors('Oh no! There were a few errors while trying to register your account.'); ?>
								</div>
							<?php } ?>
							
							<form method="post">
								<div><label class="visually_hidden" for="email_input">Email address: </label><input data-watermark="Email address" type="text" class="text_input" id="email_input" name="email" value="" /></div>
								<div><label class="visually_hidden" for="password_input">Password </label><input data-watermark="Password" type="password" class="text_input" id="password_input" name="password" /></div>
								<input type="submit" class="submit_button" value="Log me in, please!" />
							</form>
						<?php } else { ?>
							<p>Congratulations! You've successfully logged in!<p>
							<p>Returning to the home page...</p>
							<script type="text/javascript">
							function goHome(){
								window.location.pathname = '/index.php';
							}
							setTimeout('goHome()',1000);
							</script>
						<?php } ?>
					</div>
				</section>
<?php $GlobalPage->includeFile('footer.php'); ?>