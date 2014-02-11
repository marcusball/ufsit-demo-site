<!DOCTYPE html5>
<html>
	<head>
		<title>A page with errors!</title>
	</head>
	<body>
		<!-- These will produce errors as there is no associated function file -->
		<h1><?php $Page->doStuff(); ?></h1>
		<p><?php $Page->getsum; ?></p>
	</body>
</html>