<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php $GlobalPage->pageTitle(); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="/favicon.ico" type="image/ico" />
		<link rel="stylesheet" href="/res/styles/bootstrap.min.css" >
		<link rel="stylesheet" href="/res/styles/main.css" />
	</head>
	<body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/" data-toggle="tooltip" title="This sure is a legitimate website." data-placement="bottom"><?php echo SITE_NAME; ?></a>
                </div>
                <div>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="active"><a href="/">Home</a></li>
                        <li><a href="#">Page 1</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                        <?php if($GlobalPage->user->isLoggedIn()){ ?>
						<li><a href="/private/?logout" class="button">Log out</a></li>
						<?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
        
		<div class="container">
            <div class="blog-header">
                <h1 class="blog-title"><?php echo SITE_NAME; ?></h1>
                <p class="lead blog-description">This sure is a legitimate website.</p>
            </div>
            <div class="row">
                <div class="col-sm-8 blog-main">
