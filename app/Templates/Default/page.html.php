<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php print $description ?><">
	<meta name="keywords" content="<?php print $keywords ?><">
	<title><?php print $headerTitle ?></title>

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="/css/bootstrap-datetimepicker.css">
	<link rel="stylesheet" href="/css/system.css">
	<link rel="stylesheet" href="/app/Templates/Default/css/style.css">

	<script src="/script/jquery-1.11.0.min.js"></script>
	<script src="/script/moment.js"></script>
	<script src="/script/bootstrap.min.js"></script>
	<script src="/script/bootstrap-datetimepicker.js"></script>
	<script src="/script/jquery.validate.js"></script>
	<script src="/script/system.js"></script>
	<script src="/app/Templates/Default/js/script.js"></script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body class="<?php print $bodyClass ?>">

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
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li class="active"><a href="/">Home</a></li>
				<li><a href="/admin/">Admin Home</a></li>
				<li><a href="/admin/messages">Messages</a></li>
				<li><a href="/admin/messages/add">Add Messages</a></li>
				<li><a href="/api/messages/rog85dvnt8e9btv3rsaas">Messages jSon</a></li>
			</ul>
		</div>

		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<?php print $messages ?>
			<?php print $content ?>
		</div>
	</div>
</div>

</body>
</html>
