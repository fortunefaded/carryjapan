<?php

App::uses('AppModel','Model');
App::import('Vendor', 'ems');

class Qrcode extends AppModel {

	public $name = 'Qrcode';
	public $useTable = 'qrcodes';
	public $belongsTo = array('User','Package');
	
	public function getWeight($id){
		$params = array(
			'conditions' => array(
				'Qrcode.id' => $id,
			),
			'recursive' => -1,
		);
		//QRコードの情報を取得してから
		$qrcode = $this->find('first', $params);
		//商品重量を取得
		$item_weight = $qrcode['Qrcode']['weight'];
		return $item_weight;
	}
	
	public function calculatePrice($item_weight, $country = ''){
		$coefficient = 1.1;//重さに対する係数
		$countryList = ems::country_list();
		$priceList = ems::price_list();

		//配送先エリアをも取得
		$zone = $countryList[$country];
		//該当ゾーンから重さに対する金額を調べる
		foreach($priceList[$zone] as $weight => $value){
			if($item_weight * $coefficient <= $weight){
				return $value;
			}
		}
	}

	//QRコードのユニークIDが登録されているかどうかを確認する
	public function isUniqueIdRegistered($unique_id = null){
		//QRコードが登録されているか確認する
		$params = array(
			'conditions' => array(
				'Qrcode.unique_id' => $unique_id,
			),
		);
		$qrcode_data = $this->find('first', $params);
		
		if($qrcode_data){
			return true;
		} else {
			return false;
		}
	}
	
	public function getPackageWeightById($qrcode_id){
		$params = array(
			'conditions' => array(
				'Qrcode.id' => $qrcode_id,
			),
			'fields' => array(
				'Package.weight'
			),
		);
		$package = $this->find('first', $params);
		return $package['Package']['weight'];
	}
	
	public function getPackageIdById($qrcode_id){
		$params = array(
			'conditions' => array(
				'Qrcode.id' => $qrcode_id,
			),
			'fields' => array(
				'Package.id'
			),
		);
		$package = $this->find('first', $params);
		return $package['Package']['id'];
	}
}