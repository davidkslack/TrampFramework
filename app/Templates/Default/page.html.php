<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php print $this->description ?><">
	<meta name="keywords" content="<?php print $this->keywords ?><">
	<title><?php print $this->headerTitle ?></title>

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

	<style>
		.row .sidebar{z-index: -1}
		/*
 * Base structure
 */

		/* Move down content because we have a fixed navbar that is 50px tall */
		body {
			padding-top: 50px;
		}


		/*
		 * Global add-ons
		 */

		.sub-header {
			padding-bottom: 10px;
			border-bottom: 1px solid #eee;
		}


		/*
		 * Sidebar
		 */

		/* Hide for mobile, show later */
		.sidebar {
			display: none;
		}
		@media (min-width: 768px) {
			.sidebar {
				position: fixed;
				top: 51px;
				bottom: 0;
				left: 0;
				z-index: 1000;
				display: block;
				padding: 20px;
				overflow-x: hidden;
				overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
				background-color: #f5f5f5;
				border-right: 1px solid #eee;
			}
		}

		/* Sidebar navigation */
		.nav-sidebar {
			margin-right: -21px; /* 20px padding + 1px border */
			margin-bottom: 20px;
			margin-left: -20px;
		}
		.nav-sidebar > li > a {
			padding-right: 20px;
			padding-left: 20px;
		}
		.nav-sidebar > .active > a {
			color: #fff;
			background-color: #428bca;
		}


		/*
		 * Main content
		 */

		.main {
			padding: 20px;
		}
		@media (min-width: 768px) {
			.main {
				padding-right: 40px;
				padding-left: 40px;
			}
		}
		.main .page-header {
			margin-top: 0;
		}


		/*
		 * Placeholder dashboard ideas
		 */

		.placeholders {
			margin-bottom: 30px;
			text-align: center;
		}
		.placeholders h4 {
			margin-bottom: 0;
		}
		.placeholder {
			margin-bottom: 20px;
		}
		.placeholder img {
			display: inline-block;
			border-radius: 50%;
		}

	</style>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Tramp Framework</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<li class="active"><a href="/">Home</a></li>
				<li><a href="/admin/">Admin Home</a></li>
			</ul>
			<form class="navbar-form navbar-right">
				<input type="text" class="form-control" placeholder="Search...">
			</form>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li class="active"><a href="/">Home</a></li>
				<li><a href="/admin/">Admin Home</a></li>
				<li><a href="/admin/user">Users</a></li>
				<li><a href="/admin/user/add">User add</a></li>
				<li><a href="/admin/messages">Messages</a></li>
				<li><a href="/admin/messages/add">Add Messages</a></li>
			</ul>
		</div>

		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<?php print $this->viewMessages ?>
			<?php print $content ?>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
</body>
</html>
