<div class="row">
<?php foreach($addresses as $address):?>
	<div class="col-xs-12 col-sm-4">
		<table class="table table-bordered ">
			<tr class="zipcode">
				<td class="text-center">ZIPコード</td>
				<td><?php echo $address['UserAddress']['zipcode']?></td>
			</tr>
			<tr class="country">
				<td class="text-center">国</td>
				<td><?php echo $address['UserAddress']['country']?></td>
			</tr>
			<tr class="name">
				<td class="text-center">名前</td>
				<td><?php echo $address['UserAddress']['name']?></td>
			</tr>
			<tr class="address">
				<td class="text-center">住所</td>
				<td><?php echo $address['UserAddress']['address']?></td>
			</tr>
		</table>
		<?php echo $this->Form->create();?>
		<?php echo $this->Form->hidden('UserAddress.id', array('value' => $address['UserAddress']['id']))?>
		<?php echo $this->Form->end('この住所を使用する');?>
	</div>
<?php endforeach;?>
</div>
<div class="divider"></div>
<?php echo $this->Html->link('新しく住所を登録する', array('controller' => 'pay', 'action' => 'regist_address'), array('class' => 'btn outlined'))?>