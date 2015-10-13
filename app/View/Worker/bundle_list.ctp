<div class="row">
	<?php foreach($packages as $package):?>
	<div class="bundle-list col-xs-12 col-sm-3">
		<table class="table table-bordered text-center">
			<tr class="id">
				<td>ID</td>
				<td><?php echo $package['Package']['id']?></td>
			</tr>
		</table>
		<?php echo $this->Html->link('荷物の同梱処理を開始する', array('controller' => 'worker', 'action' => 'bundle', $package['Package']['id']),array('class' => 'btn gray'))?>
	</div>
	<?php endforeach;?>
</div>