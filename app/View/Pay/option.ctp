<div class="row">
	<?php echo $this->Form->create()?>
	<?php foreach($options as $option):?>
	<div class="col-xs-12 col-sm-4">
		<table class="table table-bordered text-center">
			<tr class="option">
				<td class="check-box-area" rowspan="3">
					<?php echo $this->Form->input('Option.id.', array('hiddenField' => false,'type' => 'select', 'multiple' => 'checkbox', 'options' => array($option['Option']['id'] => $option['Option']['id'])));?>
				</td>
				<td class="name"><?php echo $option['Option']['name']?></td>
				<td class="price"><?php echo $option['Option']['price']?> 円</td>
			</tr>
		</table>
	</div>
	<?php endforeach;?>
	<div class="col-xs-12">
	<?php echo $this->Form->end('住所情報の確認へ進む')?>
	</div>
</div>