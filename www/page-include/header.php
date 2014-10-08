<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php $GlobalPage->pageTitle(); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="/favicon.ico" type="image/ico" />
		<link rel="stylesheet" href="/res/styles/normalize.css" />
		<link rel="stylesheet" href="/res/styles/foundation.css" />
		<link rel="stylesheet" href="/res/styles/main.css" />
		<script src="/res/scripts/vendor/modernizr.js"></script>
	</head>
	<body>
		<nav class="row">
			<div class="large-12 columns">
				<div class="nav-bar right">
					<ul class="button-group">
						<li><a href="/" class="button">Home</a></li>
						<li><a href="/private/" class="button">Super Secret</a></li>
						<?php if($GlobalPage->user->isLoggedIn()){ ?>
						<li><a href="/private/?logout" class="button">Log out</a></li>
						<?php } ?>
						<!--<li><a href="#" class="button">Link 3</a></li>
						<li><a href="#" class="button">Link 4</a></li>-->
					</ul>
				</div>
				<h1><?php echo SITE_NAME; ?><br /><small>This sure is a legitimate website.</small></h1>
				<hr/>
			</div>
		</nav>
		<div id="body">