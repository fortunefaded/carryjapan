	<select type="text" id="destination-country" />
	</select>	
	<select type="number" id="package-weight" />
	</select>
	
	<button onclick="calculateFee()">test</button>
	<p>計算結果</p>
	<div id="calculation-result">
	</div>
	<?php echo $this->Html->script('calculatefee', array('inline' => false)); ?>