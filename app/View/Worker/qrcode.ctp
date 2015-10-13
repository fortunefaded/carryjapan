<?php echo $this->Form->create();?>
<?php echo $this->Form->input('User.unique_id', array('type' => 'text', 'label' => 'ユーザーのユニークIDを入力して下さい。'));?>
<?php echo $this->Form->end('登録する');?>