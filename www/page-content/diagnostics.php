<?php $GlobalPage->includeFile('header.php'); ?>
<h2>Diagnostic information</h2>
<pre>
<?php $Page->printDiagnostics(); ?>
</pre>
<?php $GlobalPage->includeFile('footer.php'); ?>