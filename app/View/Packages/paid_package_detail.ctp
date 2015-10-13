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
	<div class="col-xs-12">
		<?php echo $this->Html->link('お支払い済みの荷物一覧ページへ戻る', array('controller' => 'packages', 'action' => 'paid'), array('class' => 'btn gray'))?>
	</div>
</div>
<!-- <?php debug($bundled_packages);?> -->
