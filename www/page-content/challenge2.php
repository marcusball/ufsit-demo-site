<?php 
/*
 * The GlobalPage variable is available to any template file. It references the RequestHandler object in index.php.
 * You can use includeFile($filename), to include files from the /page-include/ folder. 
 * This is useful for including things like headers and footers. 
 */
$GlobalPage->includeFile('header.php'); ?>
                    <article>
                        <?php if(isset($this->message)){ ?>
                            <div class="alert <?php echo (($this->win)?'alert-success':'alert-danger'); ?>">
                                <?php echo $this->message; ?>
                                
                                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            </div>
                        <?php } ?>
                    
                        <h2>Create a new blog post</h2>
                        <h3><small>Signed in as <a href="#guest">guest</a></small></h3>
                        
                        <form method="post">
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" id="title" name="title">
                            </div>
                            
                            <div class="form-group">
                                <label for="post">Post:</label>
                                <textarea class="form-control" rows="5" id="post" name="post"></textarea>
                            </div>
                            
                            <input type="hidden" name="user" value="guest" />
                            
                            <input type="submit" class="btn btn-primary" value="Submit post" />
                        </form>
                    </article>
<?php $GlobalPage->includeFile('footer.php'); ?> 
