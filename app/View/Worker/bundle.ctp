<div class="row">
	<div class="col-xs-12">
		<h3>下記の <?php echo count($package['Qrcode'])?> 点の荷物を同梱して下さい。</h3>
	</div>
	<?php foreach($package['Qrcode'] as $qrcode):?>
	<div class="bundle-list col-xs-12 col-sm-3">
		<table class="table table-bordered text-center">
			<tr class="id">
				<td>ID</td>
				<td><?php echo $qrcode['id']?></td>
			</tr>
			<tr class="package-number">
				<td>ユニークID</td>
				<td><?php echo $qrcode['unique_id']?></td>
			</tr>
			<tr class="purchase-from">
				<td>購入元</td>
				<td><?php echo $this->Qrcode->getPurchaseFrom($qrcode['purchase_from_id'])?></td>
			</tr>
			<tr class="tracking-number">
				<td>追跡番号</td>
				<td><?php echo $qrcode['tracking_number']?></td>
			</tr>
			<tr class="date">
				<td>商品到着日</td>
				<td><?php echo $qrcode['created']?></td>
			</tr>
			<tr class="weight">
				<td>重量</td>
				<td><?php echo $qrcode['weight']?> g</td>
			</tr>
		</table>
	</div>
	<?php endforeach;?>
	<div class="col-xs-12">
		<h3>同梱処理後の荷物重量を計測し、グラムで入力して下さい。</h3>
		<?php echo $this->Form->create();?>
		<?php echo $this->Form->input('Package.weight',array('type' => 'text', 'label' => false, 'placeholder' => '例: ' . $package_weight));?>
		<?php echo $this->Form->end('同梱処理を完了')?>
	</div>
</div>