<div class="row">
	<div class="col-xs-12">
		<table class="table table-bordered">
			<tr class="charge">
				<td width="50%">配送手数料</td>
				<td width="50%"><?php echo $shipping_charge?> 円</td>
			</tr>
			<tr class="cost">
				<td width="50%">配送料</td>
				<td width="50%"><?php echo $item_price?> 円</td>
			</tr>
		</table>
		<table class="table table-bordered">
			<?php foreach($options as $option):?>
			<tr class="option">
				<td width="50%"><?php echo $option['Option']['name']?></td>
				<td width="50%"><?php echo $option['Option']['price']?> 円</td>
			</tr>
			<?php endforeach;?>
		</table>
		<table class="table table-bordered">
			<tr class="total-amount">
				<td width="50%">合計</td>
				<td width="50%"><?php echo $total_amount?> 円</td>
			</tr>
		</table>
	</div>
	<div class="col-xs-12">
		<div class="divider"></div>
	</div>
	<div class="col-xs-12 col-sm-6">
		<?php if($card_info): //カード情報が有る場合、カード情報を表示する?>
		<table class="table table-bordered">
			<tr>
				<td>名義人</td>
				<td><?php echo $card_info['name']?></td>
			</tr>
			<tr>
				<td>カード番号</td>
				<td>****-****-****-<?php echo $card_info['last4']?></td>
			</tr>
			<tr>
				<td>カード種別</td>
				<td><?php echo $card_info['type']?></td>
			</tr>
			<tr>
				<td colspan="2" class="text-center">
					<?php echo $this->Form->create(null, array('url' => array('controller' => 'pay', 'action' => 'card_info'))); ?>
					<script src="https://checkout.webpay.jp/v2/" class="webpay-button" data-lang="ja" data-key="<?php echo WEBPAY_PUBLIC_KEY; ?>" data-text='違うクレジットカードを使う' data-submit-text='カード情報を送信する' ></script>
					<?php echo $this->Form->end();?>
				</td>
			</tr>
		</table>
		<?php echo $this->Form->create(null, array('url' => array('controller' => 'pay', 'action' => 'pay_by_webpay'))); ?>
		<?php echo $this->Form->end('決済する');?>
		
		<?php else: //カード情報がない場合, カード情報を送る?>
			<?php echo $this->Form->create(null, array('url' => array('controller' => 'pay', 'action' => 'card_info'))); ?>
			<script src="https://checkout.webpay.jp/v2/" class="webpay-button" data-lang="ja" data-key="<?php echo WEBPAY_PUBLIC_KEY; ?>" data-text='クレジットカード情報を登録する' data-submit-text='カード情報を送信する' ></script>
			<?php echo $this->Form->end();?>
		<?php endif;?>
	</div>
</div>