<?php
App::uses('AppController','Controller');

class WorkerController extends AppController {

	public $name = 'Worker';
	public $uses = array('Qrcode', 'User', 'Package');
	public $helpers = array('Qrcode');
	public $layout = 'worker';

	public function index(){
		
	}
	
	public function qrcode($qrcode_unique_id = null){
		//IDがセットされていなかった場合
		if(! $qrcode_unique_id){
			$this->redirect(array('controller' => 'worker' , 'action' => 'index'));
		}
		//記号など含まれていた場合
		if(! ctype_alnum($qrcode_unique_id)){
			$this->redirect(array('controller' => 'worker' , 'action' => 'index'));
		}
		
		//QRコードが登録されている場合は詳細ページへリダイレクト
		if($this->Qrcode->isUniqueIdRegistered($qrcode_unique_id)){
			$this->redirect(array('controller' => 'worker' , 'action' => 'qrcode_detail', $qrcode_unique_id));
		}
		
		//ユーザーIDが入力されていない場合は登録画面を表示する
		if(! $this->request->data){
			$this->render();
			return;
		}
		
		//バリデーションをかける
		$this->User->set($this->request->data);
		$this->User->validate = array(
			'unique_id' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'ユーザーIDを入力してください。'
				),
			),
		);

		//バリデーションに通ったら
		if(! $this->User->invalidFields()){
			//ユーザーのユニークIDをセット
			$user_unique_id = $this->request->data['User']['unique_id'];
			//登録ロジックを呼び出す
			$this->_createQrcode($user_unique_id, $qrcode_unique_id);
		}
	}
	
	public function qrcode_detail($qrcode_unique_id = null){
		//QRコードに紐づく情報を取得する
		$params = array(
			'conditions' => array(
				'Qrcode.unique_id' => $qrcode_unique_id,
			),
		);
		$qrcode = $this->Qrcode->find('first', $params);
		$this->set('qrcode', $qrcode);
	}
	
	//QRコードにトラッキング番号を登録する
	public function regist($qrcode_unique_id = null){
		if(! $qrcode_unique_id){
			$this->redirect(array('controller' => 'worker' , 'action' => 'index'));
		}
		
		//該当ユーザーを探す
		$params = array(
			'conditions' => array(
				'Qrcode.unique_id' => $qrcode_unique_id,
			),
		);
		$qrcode = $this->Qrcode->find('first', $params);
		
		//もし該当するQRコードがなければ
		if(! $qrcode){
			$this->Session->setFlash('該当するコードが存在しません。' , 'default' , array('class' => 'danger'));
			$this->redirect(array('controller' => 'worker' , 'action' => 'index'));
		}
		
		//ドロップダウンリストのセット
		$this->loadModel('PackageCategory');
		$this->set('category', $this->PackageCategory->find('list'));
		$this->loadModel('PurchaseFrom');
		$this->set('purchase_from', $this->PurchaseFrom->find('list'));
		
		//情報が送信されていない場合は登録画面を表示する
		if(! $this->request->data){
			$this->render();
			return;
		}
		//バリデーション
		$this->Qrcode->set($this->request->data);
		$this->Qrcode->validate = array(
			'tracking_number' => array(
				array(
					'rule' => array('between', 5, 5),
					'message' => '5文字で入力して下さい。',
				),
				array(
					'rule' => array('numeric'),
					'message' => 'データは整数で入力して下さい。',
				),
				array(
					'rule' => 'notEmpty',
					'message' => '追跡番号を入力してください。'
				),
			),
			'price' => array(
				array(
					'rule' => array('numeric'),
					'message' => 'データは整数で入力して下さい。',
				),
				array(
					'rule' => 'notEmpty',
					'message' => '金額を入力してください。'
				),
			),
			'weight' => array(
				array(
					'rule' => array('numeric'),
					'message' => 'データは整数で入力して下さい。',
				),
				array(
					'rule' => 'notEmpty',
					'message' => '重量を入力してください。'
				),
			),
		);
		
		//バリデーションに通ったら
		if(! $this->Qrcode->invalidFields()){
			$this->request->data['Qrcode']['id'] = $qrcode['Qrcode']['id'];
			$this->request->data['Qrcode']['status'] = 1;
			if($this->Qrcode->save($this->request->data)){
				$this->Session->setFlash('商品データの保存に成功しました。' , 'default' , array('class' => 'success'));
				$this->redirect(array('controller' => 'worker' , 'action' => 'index'));
			}
		}
	}
	
	public function bundle_list(){
		$params = array(
			'conditions' => array(
				'Package.is_bundled' => 0,
			),
		);

		$packages = $this->Package->find('all', $params);
		$this->set('packages', $packages);
	}
	
	public function bundle($package_id = null){
		if($package_id == null){
			$this->redirect(array('controller' => 'worker' , 'action' => 'index'));
		}

		$params = array(
			'conditions' => array(
				'Package.id' => $package_id,
				'Package.is_bundled' => 0,
			),
		);

		$package = $this->Package->find('first', $params);
		
		//カラならリダイレクト
		if(! $package){
			$this->redirect(array('controller' => 'worker' , 'action' => 'index'));
		}
		
		$this->set('package', $package);
		
		//プレースホルダー用に重量計っとく
		$package_weight = 0;
		foreach($package['Qrcode'] as $qrcode){
			$package_weight += $qrcode['weight'];
		}
		$this->set('package_weight', $package_weight);
		
		//重量が入力されていない場合は登録画面を表示する
		if(! $this->request->data){
			$this->render();
			return;
		}
		
			//バリデーション
		$this->Package->set($this->request->data);
		$this->Package->validate = array(
			'weight' => array(
				array(
					'rule' => 'notEmpty',
					'message' => '重量を入力してください。'
				),
				array(
					'rule' => array('numeric'),
					'message' => 'データは整数で入力して下さい。',
				),
			),
		);
		
		//バリデーションに通ったら
		if(! $this->Package->invalidFields()){
			$package_data['Package']['id'] = $package['Package']['id'];
			$package_data['Package']['weight'] = $this->request->data['Package']['weight'];
			$package_data['Package']['is_bundled'] = 1;
			if($this->Package->save($package_data)){
				$this->Session->setFlash('同梱処理がに完了しました。' , 'default' , array('class' => 'success'));
				$this->redirect(array('controller' => 'worker' , 'action' => 'bundle_list'));
			}
		}
	}
	
	private function _createQrcode($user_unique_id = null, $qrcode_unique_id = null){
		//入力されたユニークIDを持つユーザーを探す
		$params = array(
			'conditions' => array(
				'User.unique_id' => $user_unique_id,
			),
		);

		$user_data = $this->User->find('first', $params);

		//本人確認の機構が必要
		//該当するユーザーが居た場合
		if($user_data){
			//情報を保存する
			$qrcode = array();
			$qrcode['Qrcode']['unique_id'] = $qrcode_unique_id;
			$qrcode['Qrcode']['user_id'] = $user_data['User']['id'];
			
			//登録の結果の通知を出し、
			if($this->Qrcode->save($qrcode)){
				$this->Session->setFlash('QRコードと会員データの関連付けに成功しました。' , 'default' , array('class' => 'success'));
				//詳細情報登録ページへリダイレクト
				$this->redirect(array('controller' => 'worker' , 'action' => 'regist', $qrcode_unique_id));
			} else {
				$this->Session->setFlash('QRコードと会員データの関連付けに失敗しました。' , 'default' , array('class' => 'danger'));
			}
		}
		
		//該当するユーザーが居なかった場合
		else {
			$this->Session->setFlash('入力されたIDを持つユーザーが存在しません。' , 'default' , array('class' => 'danger'));
		}
	}
}