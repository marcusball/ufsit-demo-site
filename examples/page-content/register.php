<?php $GlobalPage->includeFile('header.php'); ?>
				<section class="ca">
					<div class="box">
						<h2>Well, this sure is a registration page.</h2>
                        
						<hr />
						<?php if(!$this->registrationSuccessful()){ /* If there were errors registering, or if no registration attempt has been made */ ?>
							<?php if($this->response->hasErrors()){ /* If there were errors listed on the page, let's output them now. */ ?>
								<div id="errorsContainer">
                                <h3>Oh no! There were a few errors while trying to register your account.</h3>
								<?php $this->response->printErrors(); ?>
								</div>
							<?php } ?>
							
								
									<form method="post">
										<div><label class="visually_hidden" for="full_name_input">What is your full name? </label><input type="text" data-watermark="What is your full name? (Ex: John Smith)" id="full_name_input" name="full_name" value="<?php //$Page->outputCurrentInput('full_name'); ?>" class="text_input" /></div>
										<div><label class="visually_hidden" for="addressing_name_input">What should we call you? </label><input type="text" data-watermark="What should we call you? (Ex: John)" id="addressing_name_input" name="addressing_name" value="<?php //$Page->outputCurrentInput('addressing_name'); ?>" class="text_input" /></div>
										<div><label class="visually_hidden" for="email_input">What is your email address? </label><input type="text" data-watermark="What is your email address?" id="email_input" name="email" value="<?php //$Page->outputCurrentInput('email'); ?>" class="text_input" /></div>
										<div><label class="visually_hidden" for="password_input">What would your password to be? </label><input type="password" data-watermark="What would you like your password to be?" id="password_input" name="password" class="text_input" /></div>
										<div><label class="visually_hidden" for="password_conf_input">Please confirm your password. </label><input type="password" data-watermark="Please confirm your password." id="password_conf_input" name="password_conf" class="text_input" /></div>
										<input type="hidden" name="submit_registration" value="true" />
										<input type="submit" class="submit_button" value="I'm done, register me!" />
									</form>
								
							
						<?php } else { ?>
							<p>Congratulations! You've successfully registered! Yey!<p>
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