<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title><?php echo $this->fetch('title'); ?> | Carry Japan</title>
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
			echo $this->Html->css(array('bootstrap.min', 'drawer.min', 'style'));
			echo $this->Html->script(array('bootstrap.min', 'jquery.drawer.min', 'script'));
			
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
	</head>
	<body class="drawer drawer-left drawer-close name_<?php echo $this->name?> action_<?php echo $this->action?>">
		<header style="display:non">
		<?php if($loggedIn == true):?>
			<div class="drawer-header">
				<button type="button" class="drawer-toggle drawer-hamburger">
					<span class="sr-only">toggle navigation</span> 
					<span class="drawer-hamburger-icon"></span>
				</button>
			</div>
			<div class="drawer-main drawer-default">
				<nav class="drawer-nav" role="navigation">
					<div class="drawer-brand"><?php echo $this->Html->link('マイページ', array('controller' => 'index', 'action' => 'index'))?></div>
					<ul class="drawer-menu top">
						<li class="drawer-menu-item">
							<div class="address-box">
								<div class="address">
									<p class="head">あなたの住所は</p>
									<p>〒272-0137</p>
									<p>千葉県市川市福栄4-26-7 #<?php echo $user['unique_id']?></p>
								</div>
<!-- 								<a href="#" class="address-copy-button">住所をコピーする</a> -->
							</div>
						</li>
						<li class="divider"></li>
						<li class="drawer-menu-item"><?php echo $this->Html->link('預入荷物一覧', array('controller' => 'packages', 'action' => 'arrived'))?> <div style="display:none" class="counter count<?php echo $number_of_packages?>"><span class="count"><?php echo $number_of_packages?></span></div></li>
						<li class="divider"></li>
						<li class="drawer-menu-item"><?php echo $this->Html->link('支払い済み荷物一覧', array('controller' => 'packages', 'action' => 'paid'))?></li>
						<li class="divider"></li>
						<li class="drawer-menu-item"><?php echo $this->Html->link('無料配送を獲得', array('controller' => 'index', 'action' => 'index'))?></li>
						<li class="divider"></li>
						<li class="drawer-menu-item"><?php echo $this->Html->link('ログアウト', array('controller' => 'users', 'action' => 'logout'))?></li>
						<li class="divider"></li>
					</ul>
					<ul class="drawer-menu bottom">
						<li class="divider"></li>
						<li class="drawer-menu-item"><?php echo $this->Html->link('ご利用ガイド', array('controller' => 'guide', 'action' => 'list'))?></li>
						<li class="divider"></li>
						<li class="drawer-menu-item"><?php echo $this->Html->link('価格', array('controller' => 'charge', 'action' => 'index'))?></li>
						<li class="divider"></li>
<!--
						<li class="drawer-menu-item"><?php echo $this->Html->link('ショッピング', array('controller' => 'index', 'action' => 'index'))?></li>
						<li class="divider"></li>
-->
						<li class="drawer-menu-item"><?php echo $this->Html->link('FAQ', array('controller' => 'index', 'action' => 'index'))?></li>
						<li class="divider"></li>
<!--
						<li class="drawer-menu-item"><?php echo $this->Html->link('お知らせ', array('controller' => 'index', 'action' => 'index'))?></li>
						<li class="divider"></li>
-->
					</ul>
				</nav>
			</div>
			<?php endif;?>
			<div class="logo">
				<?php echo $this->Html->image('logo.png', array('url' => array('controller' => 'index', 'action' => 'index')))?>
			</div>
		</header>
		<h1 class="page-title"><?php echo $title_for_layout?></h1>
		<div class="container-fluid main drawer-overlay">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<footer>
		</footer>
	</body>
</html>