<div class="row">
	<div class="col-xs-12">
		<?php echo $this->Form->create();?>
		<?php echo $this->Form->input('User.password', array('label' => 'パスワード'));?>
		<?php echo $this->Form->input('User.password_confirm', array('type' => 'password', 'label' => 'パスワード再入力(確認用)'));?>
		<?php echo $this->Form->end('再設定を完了する');?>
	</div>
</div>