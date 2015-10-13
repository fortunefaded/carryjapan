<div class="row">
	<div class="col-xs-12">
		<?php echo $this->Form->create();?>
		<?php echo $this->Form->input('User.email', array('label' => '登録済みのメールアドレスを入力して下さい'));?>
		<?php echo $this->Form->end('再発行');?>
	</div>
</div>