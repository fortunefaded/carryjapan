<div>ユーザー名は <?php echo $user_name?> で間違いありませんか？</div>
<?php echo $this->Form->postlink('はい', array('controller' => 'qrcodes', 'action' => 'confirm'), array('class' => 'btn btn-primary'))?>
<?php echo $this->Html->link('いいえ(挙動を検討)', array('controller' => 'mypage', 'action' => 'send'), array('class' => 'btn btn-primary'))?>
