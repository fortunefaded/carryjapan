<div class="row">
	<div class="col-xs-12">
		<?php echo $this->Form->create();?>
		<?php echo $this->Form->input('UserAddress.name', array('label' => '名前'));?>
		<?php echo $this->Form->input('UserAddress.country', array('empty' => '国を選択して下さい', 'type' => 'select', 'label' => '国'));?>
		<?php echo $this->Form->input('UserAddress.address', array('label' => '住所'));?>
		<?php echo $this->Form->input('UserAddress.zipcode', array('label' => 'ZIPコード'));?>
		<?php echo $this->Form->input('UserAddress.tel_number', array('label' => '電話番号'));?>
		<?php echo $this->Form->end('この住所を登録する')?>
	</div>
</div>

<?php echo $this->Html->script('countrylist', array('inline' => false)); ?>