<?php echo $this->Form->create();?>
<?php echo $this->Form->input('User.password');?>
<?php echo $this->Form->password('User.password_confirm');?>
<?php echo $this->Form->end('登録する');?>