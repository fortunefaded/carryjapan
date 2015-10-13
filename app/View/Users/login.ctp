<div class="row">
	<div class="col-xs-12">
		<?php echo $this->Form->create();?>
		<?php echo $this->Form->input('User.email' , array('label' => 'メールアドレスを入力してください。'));?>
		<?php echo $this->Form->input('User.password' , array('label' => 'パスワードを入力してください。'));?>
		<?php echo $this->Form->end('ログイン');?>
		<div class="divider"></div>
		<?php echo $this->Html->link('会員登録', array('controller' => 'users', 'action' => 'signup'))?>
		<?php echo $this->Html->link('パスワードをお忘れですか？', array('controller' => 'users', 'action' => 'reissue'))?>
	</div>
</div>