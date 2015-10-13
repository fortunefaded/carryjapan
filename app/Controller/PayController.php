<?php
require ROOT . DS . 'vendor/autoload.php';
use WebPay\WebPay;
App::uses('AppController','Controller');

class PayController extends AppController {

	public $name = 'Pay';
	public $uses = array(null);
	
	public function beforeFilter(){
		//商品データの確認
		if(! $this->Session->check('Item')){
			$this->redirect(array('controller' => 'index' , 'action' => 'index'));
		}
		parent::beforeFilter();
	}

	public function option(){
		//タイトル設定
		$this->set('title_for_layout', 'オプションの選択');
		
		//オプションの一覧を表示する
		$this->loadModel('Option');
		$options = $this->Option->find('all');
		$this->set('options', $options);
		
		//オプション選択時の処理
		if($this->request->is('post')){
			if($this->request->data){
				$this->Session->delete('Item.option_id');
				$cnt = 0;
				foreach($this->request->data['Option']['id'] as $id){
					$this->Session->write('Item.option_id.' . $cnt, $id[0]);
					$cnt++;
				}
			}
			$this->redirect(array('controller' => 'pay' , 'action' => 'address'));
		}
	}
	
	public function address(){
		//タイトル設定
		$this->set('title_for_layout', 'お届け先住所の選択');

		$this->loadModel('UserAddress');
		//住所選択した時の処理
		if($this->request->is('post')){
			
			$params = array(
				'conditions' => array(
					'UserAddress.id' => $this->request->data['UserAddress']['id'],
					'UserAddress.user_id' => $this->Auth->user('id')//hiddenフィールド書き換え対策,
				),
			);
			$userAddress = $this->UserAddress->find('first', $params);
			
			//ユーザーと住所の整合性が取れなければもう一度
			if(empty($userAddress)){
				$this->redirect(array('controller' => 'pay' , 'action' => 'address'));
			}
			//一回セッション消しとく
			$this->Session->delete('Address');
			//セッションにユーザー住所のIDを保持して
			$this->Session->write('Item.address_id', $this->request->data['UserAddress']['id']);
			//決済画面へリダイレクト掛ける
			$this->redirect(array('controller' => 'pay' , 'action' => 'display'));
		}
		
		//ユーザー情報を取得
		$params = array(
			'conditions' => array(
				'UserAddress.user_id' => $this->Auth->user('id'),
			),
		);
		$addresses = $this->UserAddress->find('all', $params);
		
		//住所登録が無かったら
		if(empty($addresses)){
			$this->redirect(array('controller' => 'pay' , 'action' => 'regist_address'));
		}
		
		$this->set('addresses', $addresses);
	}
	
	public function regist_address(){
		//タイトル設定
		$this->set('title_for_layout', '新しい住所情報の登録');
			
		if(! $this->request->data){
			$this->render();
			return;
		}
	
		$this->loadModel('UserAddress');
		$this->UserAddress->set($this->request->data);
		$this->UserAddress->validate = array(
			'name' => array(
				array(
					'rule' => 'notEmpty',
					'message' => '名前を入力してください。'
				),
			),
			'country' => array(
				array(
					'rule' => 'notEmpty',
					'message' => '国を入力してください。'
				),
			),
			'address' => array(
				array(
					'rule' => 'notEmpty',
					'message' => '住所を入力してください。'
				),
			),
			'zipcode' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'ZIPコードを入力してください。'
				),
			),
			'tel_number' => array(
				array(
					'rule' => 'notEmpty',
					'message' => '電話番号を入力してください。'
				),
			),
		);
		//バリデーションに通ったら
		if(! $this->UserAddress->invalidFields()){
			$this->request->data['UserAddress']['user_id'] = $this->Auth->user('id');
			if($this->UserAddress->save($this->request->data)){
				//メッセージ持たせてリダイレクト
				$this->Session->setFlash('住所を登録しました。' , 'default' , array('class' => 'success'));
				$this->redirect(array('controller' => 'pay' , 'action' => 'address'));
			}
		}
	}
	
	public function display(){
		//タイトル設定
		$this->set('title_for_layout', '決済情報を確認');
		
		//セッション内のアイテム情報を取得する
		$price_data = $this->__getPriceData();
		
		//オプション情報取得
		$this->loadModel('Option');
		$params = array(
			'conditions' => array(
				'Option.id' => $this->Session->read('Item.option_id'),
			),
		);
		$options = $this->Option->find('all', $params);
		
		$this->set('options', $options);
		$this->set('shipping_charge', $price_data['shipping_charge']);
		$this->set('delivery_fee', $price_data['delivery_fee']);
		$this->set('total_amount', $price_data['total_amount']);
		$this->set('item_price', $price_data['item_price']);

		//WEBPAYコードを持っていれば
		$this->loadModel('User');
		if($this->User->hasWebpayCode($this->Auth->user('id'))){
			//WEBPAYコードを取得し
			$webpay_code = $this->User->getWebpayCodeById($this->Auth->user('id'));
			//$card_infoにカード情報を渡す
			$card_info = $this->User->retrieveCardInfoByWebpayCode($webpay_code);
			if($card_info){
				$this->set('card_info',$card_info);
				return;
			}
		}
		//カード情報がない場合
		$this->set('card_info',false);
	}
	
	public function card_info(){
	
		//トークンが送信されていなければ
		if(! $this->request->is('post') || ! $this->request->data['webpay-token']){
			$this->Session->setFlash('webpayトークンが送信されていません' , 'default' , array('class' => 'danger'));
			$this->redirect(array('controller' => 'pay', 'action' => 'display'));
		}

		//カード情報云々でつかう
		$this->loadModel('User');
		//ユーザーがトークン情報を送ってきたときの処理 //つまり、カード情報を登録したい
		try{
			$this->User->createWebpayCustomerByToken($this->Auth->user('id'), $this->request->data['webpay-token']);
		}
		catch(\WebPay\ErrorResponse\InvalidRequestException $e){
			$this->Session->setFlash('無効なトークン'. $e->getMessage());
			$this->redirect(array('controller' => 'pay', 'action' => 'display'));
		}
		catch(Exception $e){
			$this->Session->setFlash('予期せぬエラー'. $e->getMessage());
			$this->redirect(array('controller' => 'pay', 'action' => 'display'));
		}
		$this->Session->setFlash('新しいカード情報が登録されました' , 'default' , array('class' => 'success'));
		$this->redirect(array('controller' => 'pay', 'action' => 'display'));
	}
	
	public function pay_by_webpay(){
	
		if(! $this->request->is('post')){
			$this->redirect(array('controller' => 'index', 'action' => 'index'));
		}
		
		//購入トライ！
		try{
			//成功時
			if($this->__do_pay()){
				//need comments
				$this->__update_package_state();
				//決済終了のためセッション内の関連情報をすべて削除する
				$this->Session->delete('Item');
				$this->Session->setFlash('決済に成功しました。' , 'default' , array('class' => 'success'));
				$this->redirect(array('controller' => 'packages', 'action' => 'paid'));
			}
		} catch (\WebPay\ErrorResponse\CardException $e) {
			// カードが拒否された場合
			$this->Session->setFlash('カードが拒否されました: ' . $e->getMessage());
			$this->redirect(array('controller' => 'pay', 'action' => 'display'));
		} catch (\WebPay\ErrorResponse\InvalidRequestException $e) {
			// リクエストで指定したパラメータが不正な場合
			$this->Session->setFlash('パラメータが不正です。: ' . $e->getMessage());
			$this->redirect(array('controller' => 'pay', 'action' => 'display'));
		} catch (\WebPay\ErrorResponse\AuthenticationException $e) {
			// 認証に失敗した場合
			$this->Session->setFlash('認証が失敗しました: ' . $e->getMessage());
			$this->redirect(array('controller' => 'pay', 'action' => 'display'));
		} catch (\WebPay\ErrorResponse\ApiException $e) {
			// WebPayのサーバでエラーが起きた場合
			$this->Session->setFlash('Webpayサーバーでエラーが起こりました: ' . $e->getMessage());
			$this->redirect(array('controller' => 'pay', 'action' => 'display'));
		} catch (\WebPay\ApiConnectionException $e) {
			// APIへの接続エラーが起きた場合
			$this->Session->setFlash('WebpayAPIに接続できません: ' . $e->getMessage());
			$this->redirect(array('controller' => 'pay', 'action' => 'display'));
		} catch (\WebPay\InvalidRequestException $e) {
			// リクエストで指定したパラメータが不正で、リクエストがおこなえなかった場合
			$this->Session->setFlash('リクエストで指定したパラメータが不正です: ' . $e->getMessage());
			$this->redirect(array('controller' => 'pay', 'action' => 'display'));
		} catch (\Exception $e) {
			// WebPayとは関係ない例外の場合
			$this->Session->setFlash('決済に失敗しました: ' . $e->getMessage());
			$this->redirect(array('controller' => 'pay', 'action' => 'display'));
		}
	}
	
	private function __getPriceData(){

		//配送手数料
		$shipping_charge = 150;
		//配送料
		$delivery_fee = 0;

		//オプションの価格情報取得
		$this->loadModel('Option');
		$params = array(
			'conditions' => array(
				'Option.id' => $this->Session->read('Item.option_id'),
			),
			'fields' => array('Option.id', 'Option.price'),
		);
		$options = $this->Option->find('all', $params);
		
		//商品の価格を取得
		$item_price = $this->__getItemPrice();
		
		//合計金額を計算
		//オプションで掛かる額を計算
		$option_total_cost = 0;
		foreach ($options as $option){
			$option_total_cost += $option['Option']['price'];
		}
		
		//値のセット
		$total_amount = $shipping_charge + $delivery_fee + $option_total_cost + $item_price;
		
		$price_data = array(
			'total_amount' => $total_amount,
			'shipping_charge' => $shipping_charge,
			'delivery_fee' => $delivery_fee,
			'option_total_cost' => $option_total_cost,
			'item_price' => $item_price,
		);
		
		return $price_data;
	}
	
	private function __getItemPrice(){
		//セットされているQRコードを取得
		$item_ids = $this->Session->read('Item.qrcode_id');
		//同梱商品か否かを調べる
		$count = count($item_ids);
				
		$params = array(
			'conditions' => array(
				'UserAddress.id' => $this->Session->read('Item.address_id'),
			),
			'fields' => array(
				'UserAddress.id',
				'UserAddress.country',
			),
			'recursive' => -1,
		);
		
		$this->loadModel('UserAddress');
		$address = $this->UserAddress->find('first',$params);
		$country = $address['UserAddress']['country'];
		
		$this->loadModel('Qrcode');
		//合計金額
		$total_amount = 0;
		//もし1個だった場合、それは個別配送。QRコードに登録した重量そのまま利用して金額を計算する
		if($count === 1){
			$package_weight = $this->Qrcode->getPackageWeightById($item_ids[0]);
			$total_amount += $this->Qrcode->calculatePrice($package_weight, $country);
		}
		//同梱された商品だった場合はPackageに紐ついた重さを調べる
		else {
			//need fix
			$package_weight = $this->Qrcode->getPackageWeightById($item_ids[0]);
			$total_amount += $this->Qrcode->calculatePrice($package_weight, $country);
		}
		
		return $total_amount;
	}

	
	private function __do_pay(){
	
		//webpayコードを取得する Authから取らないのは新規登録・更新の際はAuthが更新されないため
		$this->loadModel('User');
		$cus_code = $this->User->getWebpayCodeById($this->Auth->user('id'));

		//カート情報なければ
		if(! $this->Session->check('Item')){
			throw new Exception("カートに商品がありません");
		}
		
		//Webpayコードがなければ
		if(! $cus_code){
			throw new Exception("カード情報がありません");
		}
		
		//カート内の情報を取得
		$price_data = $this->__getPriceData();
		
		//Webpayでは50円以下は取り扱えない
		if($price_data['total_amount'] < 50){
			return false;
		}
		//課金！
		$webpay = new WebPay(WEBPAY_PRIVATE_KEY);
		$result = $webpay->charge->create(array(
			"amount" => intval($price_data['total_amount'], 10),
			"currency"=>"jpy",
			"customer" => $cus_code,
			"description" => "テストケース"
		));
		
		if($result->paid){
			return true;
		}
		//paidをみる
		throw new Exception("何らかの理由で決済に失敗しました。");
	}
	
	private function __update_package_state(){
		//ここでパッケージ作らせるのってどうなのよ？
		
		$qrcode_id = $this->Session->read('Item.qrcode_id');
		//パッケージが生成されているかどうかをチェックする
		//生成されていなければパッケージを作る
		if(! $this->____has_created_package_package($qrcode_id)){
			$package_id = $this->__create_package_and_make_qrcode_packaged($qrcode_id);
		} 
		//生成されていればIDを取得
		else{
			$this->loadModel('Qrcode');
			$package_id = $this->Qrcode->getPackageIdById($qrcode_id[0]);
		}
		$this->loadModel('Package');
		if($this->Package->changePaymentStatusAsPaidById($package_id));
	}
	
	//パッケージが生成されているかどうかをチェックする
	private function ____has_created_package_package($qrcode_id){
		//QRコードが一つしかない場合はpackageは生成されていない
		$is = count($qrcode_id) !== 1 ? true : false;
		return $is;
	}
	
	//返り値はパッケージのID
	private function __create_package_and_make_qrcode_packaged($qrcode_id){
		//あとでこの辺りのフロー整備必要、とりあえず動かす、関数名はわかりやすく変えてもいいし
		//Packageモデルのロジックと統合してもいい
		$this->loadModel('Qrcode');
		$params = array(
			'conditions' => array(
				'Qrcode.id' => $qrcode_id[0],
			),
			'recursive' => -1,
		);
		$qrcode = $this->Qrcode->find('first', $params);
		
		//Packageを作る
		$this->loadModel('Package');
		$created_package = $this->Package->createPackage($this->Auth->user('id'));
		
		$package_id = $created_package['Package']['id'];
	
		$qrcode['Qrcode']['package_id'] = $package_id;
		$qrcode['Qrcode']['is_packaged'] = 1;
		if($this->Qrcode->save($qrcode)){
			return $package_id;
		}
		return false;
	}
	
	private function __createWebpayCharge(){
		
	}
}