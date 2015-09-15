<?php $GlobalPage->includeFile('header.php'); ?>
<!--
Note to self: These are some interesting words.


chihuahua
implosion
feminism
pugs
entomology
snoop
pitbull
ethernet

-->

                <article>
                    <h2>Login to admin dashboard</h2>
                    
                    
                    <?php if(isset($this->message)){ ?>
                            <div class="alert <?php echo (($this->win)?'alert-success':'alert-danger'); ?>">
                                <?php echo $this->message; ?>
                                
                                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            </div>
                        <?php } ?>
                    
                    <?php if(!$this->win){ ?>
                    <form method="post">
                        <fieldset>
                            <legend>Enter secret word</legend>
                            <input type="text" class="form-control" name="word1" />
                        </fieldset>
                        <fieldset>
                            <legend>Enter secret number</legend>
                            <input type="number" class="form-control" name="number1" max="20" />
                        </fieldset>
                        
                        <input type="submit" class="btn btn-primary" value="Submit" />
                    </form>
                    <?php } else { ?>
                        <img src="/res/images/well-done.png" class="img-responsive" />
                    <?php } ?>
                </article>




<?php $GlobalPage->includeFile('footer.php'); ?>