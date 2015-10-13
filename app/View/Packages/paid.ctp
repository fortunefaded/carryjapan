<div class="row">
	<?php foreach($bundled_packages as $bundled_package):?>
	<div class="package-data bundled col-xs-12 col-sm-3">
		<table class="table table-bordered text-center">
			<tr>
				<td>何を表示するか</td>
				<td>まよう</td>
			</tr>
		</table>
		<?php echo $this->Html->link('内容を確認する', array('controller' => 'packages', 'action' => 'paid_package_detail', $bundled_package['Package']['id']),array('class' => 'btn'))?>
	</div>
	<?// debug($bundled_packages)?>
	<?php endforeach;?>
</div>