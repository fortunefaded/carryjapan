<div class="row">
	<?php foreach($bundled_packages as $bundled_package):?>
	<div class="package-data bundled col-xs-12 col-sm-3">
		<table class="table table-bordered text-center">
<!--
			<tr class="amount">
				<td>おまとめ数</td>
				<td><?php echo count($bundled_packages[0]['Qrcode'])?></td>
			</tr>
-->
			<tr class="status">
				<td><?php echo $this->Package->getStatus($bundled_package['Package']['is_bundled'])?></td>
			</tr>
		</table>
		<?php if($bundled_package['Package']['is_bundled'] == 0):?>
		<?php echo $this->Html->link('ご依頼の内容を確認する', array('controller' => 'packages', 'action' => 'package_detail', $bundled_package['Package']['id']),array('class' => 'btn gray'))?>
		<?php else:?>
		<?php echo $this->Html->link('荷物を発送する', array('controller' => 'packages', 'action' => 'package_detail', $bundled_package['Package']['id']),array('class' => 'btn'))?>
		<?php endif;?>
	</div>
	<?php endforeach;?>
	<?php foreach($packages as $package):?>
	<div class="package-data col-xs-12 col-sm-3">
		<table class="table table-bordered text-center">
			<tr class="weight">
				<td>重量</td>
				<td><?php echo $package['Qrcode']['weight']?> g</td>
			</tr>
			<tr class="tracking-number">
				<td>追跡番号</td>
				<td><?php echo $package['Qrcode']['tracking_number']?></td>
			</tr>
			<tr class="date">
				<td>商品到着日</td>
				<td><?php echo $package['Qrcode']['created']?></td>
			</tr>
			<tr class="is-pack-all">
				<td>おまとめ梱包</td>
				<td>
					<?php echo $this->Qrcode->combine($package['Qrcode']['is_combine'])?>
				</td>
			</tr>
		</table>
		<?php if($package['Qrcode']['is_combine'] == 0):?>
		<?php echo $this->Html->link('荷物を個別配送する', array('controller' => 'packages', 'action' => 'item_detail', $package['Qrcode']['id']),array('class' => 'btn gray'))?>
		<?php else:?>
		<?php echo $this->Html->link('荷物を発送する', array('controller' => 'packages', 'action' => 'item_detail', $package['Qrcode']['id']),array('class' => 'btn'))?>
		<?php endif;?>
	</div>
	<?php endforeach?>
	<?php if($is_combinable):?>
	<div class="col-xs-12">
		<div class="divider"></div>

<!-- 		<h2>荷物をおまとめ配送する場合はこちら</h2> -->
		<?php echo $this->Html->link('荷物を一つにまとめる', array('controller' => 'packages', 'action' => 'combine'),array('class' => 'btn large'))?>
	</div>
	<?php endif;?>
</div>