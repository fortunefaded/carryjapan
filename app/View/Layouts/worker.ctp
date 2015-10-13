<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>WorkStation for CarryJapan</title>
		<!-- Bootstrap -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/iScroll/5.1.1/iscroll-min.js"></script>
		<script src="//cdn.rawgit.com/ungki/bootstrap.dropdown/3.3.1/dropdown.min.js"></script>
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<?php
			echo $this->Html->css(array('bootstrap.min','style'));
			echo $this->Html->script(array('bootstrap.min'));
			
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
	</head>
	<body class="worker">
		<div id="header">
			<nav class="navbar navbar-default">
			  <div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#">WorkStation for CarryJapan</a>
					</div>
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav navbar-right">
							<li><?php echo $this->Html->link('同梱待ちの荷物', array('controller' => 'worker', 'action' => 'bundle_list'))?></li>
						</ul>
					</div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>
		</div><!-- header -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12 main">
					<?php echo $this->Session->flash();?>
					<?php echo $this->fetch('content');?>
				</div>
			</div>
		</div>
	</body>
</html>