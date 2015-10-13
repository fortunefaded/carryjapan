<div class="row">
<?php foreach($bundled_packages['Qrcode'] as $package):?>
	<div class="col-xs-12 col-sm-4">
		<table class="table table-bordered">
			<tr class="package-number">
				<td>荷物番号</td>
				<td><?php echo $package['unique_id']?></td>
			</tr>
			<tr class="purchase-from">
				<td>購入元</td>
				<td><?php echo $this->Qrcode->getPurchaseFrom($package['purchase_from_id'])?></td>
			</tr>
			<tr class="tracking-number">
				<td>追跡番号</td>
				<td><?php echo $package['tracking_number']?></td>
			</tr>
			<tr class="date">
				<td>商品到着日</td>
				<td><?php echo $package['created']?></td>
			</tr>
			<tr class="weight">
				<td>重量</td>
				<td><?php echo $package['weight']?> g</td>
			</tr>
		</table>	
	</div>
<?php endforeach;?>
<?php if($is_bundled):?>
	<div class="col-xs-12 col-sm-6">
		<?php echo $this->Form->create()?>
		<?php echo $this->Form->end('荷物を配送する')?>
	</div>
	<div class="col-xs-12 col-sm-6">
		<?php echo $this->Html->link('荷物一覧ページへ戻る', array('controller' => 'packages', 'action' => 'arrived'), array('class' => 'btn gray'))?>
	</div>
<?php else:?>
	<div class="col-xs-12">
		<?php echo $this->Html->link('荷物一覧ページへ戻る', array('controller' => 'packages', 'action' => 'arrived'), array('class' => 'btn gray'))?>
	</div>
<?php endif;?>
</div>
<!-- <?php debug($bundled_packages);?> -->
