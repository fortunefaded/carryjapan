<?php

App::uses('AppModel','Model');
App::uses('Qrcode', 'Model');

class Package extends AppModel {

	public $name = 'Package';
	public $useTable = 'packages';
	public $hasMany = array('Qrcode');
	public $hasOne = array('Receipt');

	public function combinePackages($qrcode_ids = null, $user_id = null){
		//変数がカラor配列じゃなかったらfalse
		if(! is_array($qrcode_ids)){
			return false;
		}
		
		$created_package = $this->createPackage($user_id);

		//保存した瞬間ID発行されるのでそれを取得しておく
		$package_id = $created_package['Package']['id'];
		//Qrcodeモデルをインスタンス化
		$qr = new Qrcode;
		$params = array('Qrcode.id' => $qrcode_ids);
		
		//パッケージIDをqrcodeに持たせ、ステータスを更新。同梱済みにする
		$update_field = array('package_id' => $package_id, 'is_packaged' => 1);
		
		//該当IDのレコードをすべて更新する
		if($qr->updateAll($update_field, $params)){
			return true;
		}
		
		return false;
	}
	
	public function createPackage($user_id = null){
		if($user_id == null){
			return false;
		}
		//データ作る
		$package = $this->create();
		//ユーザーIDだけ持たせて保存
		$package['Package']['user_id'] = $user_id;
		$created_package = $this->save($package);
		return $created_package;
	}
	
	public function changePaymentStatusAsPaidById($id){
		$package['Package']['id'] = $id;
		$package['Package']['has_paid'] = 1;
		if($this->save($package)){
			return true;
		}
		return false;
	}
}