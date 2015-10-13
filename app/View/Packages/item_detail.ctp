<div class="row package-data">
	<div class="col-xs-12">
		<table class="table table-bordered">
			<tr class="package-number">
				<td>荷物番号</td>
				<td><?php echo $package['Qrcode']['unique_id']?></td>
			</tr>
			<tr class="purchase-from">
				<td>購入元</td>
				<td><?php echo $this->Qrcode->getPurchaseFrom($package['Qrcode']['purchase_from_id'])?></td>
			</tr>
			<tr class="tracking-number">
				<td>追跡番号</td>
				<td><?php echo $package['Qrcode']['tracking_number']?></td>
			</tr>
			<tr class="date">
				<td>商品到着日</td>
				<td><?php echo $package['Qrcode']['created']?></td>
			</tr>
			<tr class="weight">
				<td>重量</td>
				<td><?php echo $package['Qrcode']['weight']?> g</td>
			</tr>
			<tr class="is-pack-all">
				<td>おまとめ梱包</td>
				<td>
					<?php echo $this->Qrcode->combine($package['Qrcode']['is_combine'])?>
				</td>
			</tr>
		</table>
	</div>
	<div class="col-xs-12 col-sm-6">
		<?php echo $this->Form->create()?>
		<?php echo $this->Form->end('荷物を個別配送する')?>
	</div>
	<div class="col-xs-12 col-sm-6">
		<?php echo $this->Html->link('荷物一覧ページへ戻る', array('controller' => 'packages', 'action' => 'arrived'), array('class' => 'btn gray'))?>
	</div>
</div>