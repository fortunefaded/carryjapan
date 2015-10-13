<style type="text/css">header{display: none!important}</style>
<div class="row">
	<?php echo $this->Form->create()?>
	<?php foreach($packages as $package):?>
	<div class="package-data col-xs-12 col-sm-6">
		<table class="table table-bordered">
			<tr class="weight">
				<td class="check-box-area" rowspan="3">
					<?php echo $this->Form->input('Qrcode.id.', array('type' => 'select', 'multiple' => 'checkbox', 'hiddenField' => false, 'selected' => $package['Qrcode']['id'], 'options' => array($package['Qrcode']['id'] => $package['Qrcode']['id'])));?>
				</td>
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
		</table>
	</div>
	<?php endforeach?>
	<div class="col-xs-12 disabled-check">
		<p class="information">注意事項をよく読み、チェックボックスにチェックを入れて下さい。その段階で注意事項について承認したことになります。</p>
		<table class="table">
			<tr>
				<td width="45"><input type="checkbox" id="disabled-check"/><label for="disabled-check"></label></td>
				<td>重量や大きさにより１つのBOXにまとめられない場合は２つ以上のおまとめ梱包代がかかる場合がございます。</td>
			</tr>
		</table>
		<?php echo $this->Form->end(array('label' => 'チェックした商品を一つにまとめる', 'id' => 'disabled-submit'))?>
	</div>
</div>
