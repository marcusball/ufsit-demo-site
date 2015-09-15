<?php 
namespace pirrs;
$GlobalPage->includeFile('header.php'); 
            if($this->request->user->isLoggedIn()){ ?>
                <h1>Hello, <?php es($this->request->user->getAddressingName()); ?></h1>
                <p>Great job, you made it through the first test!</p>
            <?php
            } else{ ?>
                <h3>Uh oh, looks like something went wrong.</h3>
                <p>Maybe you should try authorizing again.</p>
                <?php 
                if($this->response->hasErrors()){
                    $this->response->printErrors();
                }
                ?>
            <?php } ?>
<?php $GlobalPage->includeFile('footer.php'); ?>