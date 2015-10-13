<?php if(! $loggedIn):?>
<div class="row head">
	<div class="col-xs-6 signup">
		<?php echo $this->Html->link('新規会員登録', array('controller' => 'users', 'action' => 'signup'),array('class' => 'btn'))?>
	</div>
	<div class="col-xs-6 login">
		<?php echo $this->Html->link('ログイン', array('controller' => 'users', 'action' => 'login'),array('class' => 'btn'))?>
	</div>
</div>
<?php endif;?>
<div class="row visual">
	<?php echo $this->Html->image('test', array('controller' => 'users', 'action' => 'signup'),array('class' => 'btn'))?>
</div>