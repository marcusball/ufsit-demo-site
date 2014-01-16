<!DOCTYPE html>
<html>
	<head>
		<title><?php $Page->pageTitle(); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="/favicon.ico" type="image/ico" />
		<link rel="stylesheet" type="text/css" href="/res/styles/main.css" />
	</head>
	<body>
		<div id="container">
			<header>
				<div id="logo">
					<h1>Pirrs</h1>
				</div>
				<nav>
					<ul>
						<li><a href="/">Home</a></li>
						<li><a href="#">Buttes</a></li>
					</ul>
				</nav>
			</header>
			<div id="body">
				<?php $Page->createBody(); ?>
			</div>
		</div>
	</body>
</html>