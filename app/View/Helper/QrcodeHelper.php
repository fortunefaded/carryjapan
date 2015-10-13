<?php
App::import('Model', 'PurchaseFrom'); 
class QrcodeHelper extends AppHelper {
	
	function getPurchaseFrom($purchase_from_id = null){
		$pf = new PurchaseFrom();
		$params = array(
			'conditions' => array(
				'PurchaseFrom.id' => $purchase_from_id
			),
		);
		$data = $pf->find('first', $params);
		return $data['PurchaseFrom']['name'];
	}
	
	function combine($num = null){
		return $num == 0 ? '同梱可能' : '同梱不可';
	}
	
	function status($num = null){
		switch($num){
			case 0:
				return '商品は倉庫に到着し、配送料の計算を行っています。';
				break;
			case 1:
				return '未決済';
				break;
			case 2:
				return '決済済み';
				break;
		}
	}
}
?>