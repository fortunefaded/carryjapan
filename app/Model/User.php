<?php
require ROOT . DS . 'vendor/autoload.php';
use WebPay\WebPay;

App::uses('AppModel','Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {

	public $name = 'User';
	public $useTable = 'users';
	public $hasMany = array(
		'Qrcode'
	);
		
	public function beforeSave($options = null){
		//passwordフィールドは必ず保存前にハッシュ化する
		if(!empty($this->data['User']['password'])){
			$passwordHasher = new SimplePasswordHasher();
			$this->data['User']['password'] = $passwordHasher->hash($this->data['User']['password']);
			return true;
		}
	}
	
	public function confirm($check){
		foreach($check as $key => $value){
			//フォームへの入力が無ければfalseを返し
			if(! isset($this->data[$this->name][$key.'_confirm'])){
				return false;
			}
			//$this->data['Example']['example']と$this->data['Example']['example_confirm']への入力が一致しない場合もfalseを返し
			if($value !== $this->data[$this->name][$key.'_confirm']){
				return false;
			}
		}
		//そのどちらでもない場合はtrueを返す
		return true;
	}
	
	public function getWebpayCodeById($id){
		$user = $this->find('first', array(
			'conditions' => array('User.id' => $id),
			'fields' => array('User.id', 'User.webpay_code'),
		));
		return $user['User']['webpay_code'];
	}
	
	//キャッシュ機能を将来的に実装したい
	public function countNumberOfArrivedPackages($user_id){
		
		if($user_id == null){
			return null;
		}

		$params = array(
			'conditions' => array(
				'id' => $user_id,
			),
		);
		
		$user_data = $this->find('first', $params);
		$count = 0;
		foreach ($user_data['Qrcode'] as $key){
			$count++;
		}
		return $count;
	}
	
	public function hasWebpayCode($user_id){
		if(! $user_id)
		{
			throw new Exception('id がありません');
		}
		$webpay_code = $this->getWebpayCodeById($user_id);
		return $webpay_code !== '';//cusで始まるかどうかにしたい!!!!!
	}
	
	private function startWebpay(){
		if(!$this->webpay)
			$this->webpay = new WebPay(WEBPAY_PRIVATE_KEY);
	}
	
	public function createWebpayCustomerByToken($user_id, $token){
		//webpay_codeを取得
		$webpay_code = $this->getWebpayCodeById($user_id);
		//インスタンス化
		$this->startWebpay();
		//$userにwebpay_codeが登録されているか否かで、update かcreateか変わる。
		//すでに顧客情報があるならばupdate
		if($webpay_code){
			try{
				$this->webpay->customer->update(array(
					"card" => $token,
					"id" => $webpay_code,
				));
				//カード情報更新完了
				return true;
			}
			catch(\WebPay\ErrorResponse\InvalidRequestException $ex){
				//例外起こったら次のifに引っ掛ける
				$webpay_code = null;
			}
		}

		//ないならばcreate  else文でないのは、上のif文で顧客IDが無効の場合を想定しているから
		if(! $webpay_code){
			//createしてWEBPAY側に送る
			$customer = $this->webpay->customer->create(array("card" => $token));
			//変更されたWebpayコードを持たせて
			$user['User']['id'] = $user_id;
			$user['User']['webpay_code'] = $customer->id;
			//保存。上手く行ったらtrue
			if($this->save($user)){
				return true;
			};
		}
		//上手く行かなかったらfalse
		return false;
	}

	public function retrieveCardInfoByWebpayCode($webpay_code){
		//インスタンス化
		$this->startWebpay();
		try{
			$customer = $this->webpay->customer->retrieve($webpay_code);
			
			//ユーザーが削除されていれば失敗する
			if($customer->deleted){
				return false;
			}

			//Viewにわたす
			//customerの情報を取得
			$card_info = array(
				'last4' => $customer->active_card->last4,
				'name' => $customer->active_card->name,
				'type' => $customer->active_card->type,
			);
			
			return $card_info;
		}
		//例外が帰って来ても失敗する
		catch(\WebPay\ErrorResponse\InvalidRequestException $e){
			return false;
		}
	}
	
}