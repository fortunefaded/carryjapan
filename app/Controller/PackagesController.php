<?php

App::uses('AppController','Controller');

class PackagesController extends AppController {

	public $name = 'Packages';
	public $uses = array('Package','Qrcode');
	public $helpers = array('Package');

	//到着済み荷物
	public function arrived(){
		//タイトル設定
		$this->set('title_for_layout', '到着済み荷物の一覧');
		//倉庫に到着済み且つ未決済の荷物の一覧を取得する
		$params1 = array(
			'conditions' => array(
				'Qrcode.user_id' => $this->Auth->user('id'),
				'Qrcode.is_packaged' => 0,/*未同梱商品のみ*/
			),
		);
	
		$packages = $this->Qrcode->find('all', $params1);
		$this->set('packages', $packages);
		
		//同梱可能な商品が2つ以上あるかないか、なければfalseを返す
		$params3 = array(
			'conditions' => array(
				'Qrcode.user_id' => $this->Auth->user('id'),
				'Qrcode.is_packaged' => 0,/*未同梱商品*/
				'Qrcode.is_combine' => 0,/*同梱可能商品*/
			),
		);
		$is_combinable = $this->Qrcode->find('count', $params3) > 1 ? true : false;
		$this->set('is_combinable', $is_combinable);

		//倉庫に到着済み且つ未決済の荷物の一覧を取得する
		$params2 = array(
			'conditions' => array(
				'Package.user_id' => $this->Auth->user('id'),
				'Package.has_paid' => 0,
			),
		);
	
		$bundled_packages = $this->Package->find('all', $params2);
		$this->set('bundled_packages', $bundled_packages);

		//もし荷物がなければ
		if(! $packages && ! $bundled_packages){
			$this->redirect(array('controller' => 'packages' , 'action' => 'no_package'));
		}
	}
	
	public function paid(){
		//タイトル設定
		$this->set('title_for_layout', 'お支払い済み荷物の一覧');
		$params = array(
			'conditions' => array(
				'Package.user_id' => $this->Auth->user('id'),
				'Package.has_paid' => 1,
			)
		);
		$bundled_packages = $this->Package->find('all', $params);
		$this->set('bundled_packages', $bundled_packages);
	}
	
	public function paid_package_detail($package_id = null){
		//タイトル設定
		$this->set('title_for_layout', 'お支払い済み荷物の詳細');
		if($package_id == null){
			$this->redirect(array('controller' => 'packages' , 'action' => 'paid'));
		}
		$params = array(
			'conditions' => array(
				'Package.id' => $package_id,
				'Package.user_id' => $this->Auth->user('id'),
			),
		);
		
		$bundled_packages = $this->Package->find('first', $params);
		$this->set('bundled_packages', $bundled_packages);
	}
	
	public function package_detail($package_id = null){
		//タイトル設定
		$this->set('title_for_layout', 'おまとめされたお荷物の情報');
	
		if($package_id == null){
			$this->redirect(array('controller' => 'index' , 'action' => 'index'));
		}
		
		$params = array(
			'conditions' => array(
				'Package.id' => $package_id,
				'Package.user_id' => $this->Auth->user('id'),
			),
		);
		
		$bundled_packages = $this->Package->find('first', $params);
		$this->set('bundled_packages', $bundled_packages);
		
		//同梱済みならtrue
		$is_bundled = $bundled_packages['Package']['is_bundled'] != 0 ? true : false;
		$this->set('is_bundled', $is_bundled);
		
		if($this->request->is('post')){
			//
			if(! $is_bundled){
				$this->redirect(array('controller' => 'packages' , 'action' => 'arrived'));
			}
			
			//qrcodeのidを配列に突っ込んで
			$qrcode_ids = array();
			foreach($bundled_packages['Qrcode'] as $package){
				array_push($qrcode_ids, $package['id']);
			}
			//qrcodeのidをセッションに保存する
			if($this->__registQrcodeToSession($qrcode_ids)){
				$this->redirect(array('controller' => 'pay' , 'action' => 'option'));
			}
		}
	}
	
	public function no_package(){
		//タイトル設定
		$this->set('title_for_layout', '該当するお荷物はありません');
	}
	
	public function item_detail($qrcode_id = null){
		//タイトル設定
		$this->set('title_for_layout', 'お荷物の情報');
		
		if($qrcode_id == null){
			$this->redirect(array('controller' => 'index' , 'action' => 'index'));
		}
		
		if($this->request->is('post')){
			$this->__registQrcodeToSession($qrcode_id);
			$this->redirect(array('controller' => 'pay' , 'action' => 'option'));
		}
		
		$params = array(
			'conditions' => array(
				'Qrcode.id' => $qrcode_id,
			),
		);
		$package = $this->Qrcode->find('first', $params);
		$this->set('package', $package);
	}
	
	public function combine(){

		if($this->request->is('post')){
			//2つ以上チェックされているか確認しておく
			if($this->_isCheckedTwoOrMore($this->request->data['Qrcode']['id'])){
				//postされたデータの整形
				$formated_qrcode_ids = $this->_formatQrcodeIds($this->request->data['Qrcode']['id']);
				//チェックされた商品を同梱する
				if($this->Package->combinePackages($formated_qrcode_ids, $this->Auth->user('id'))){
					$this->Session->setFlash('荷物の梱包依頼を受け付けました。' , 'default' , array('class' => 'success'));
					$this->redirect(array('controller' => 'packages' , 'action' => 'arrived'));
				}
			}
			//ブラウザの戻る対策
			elseif(! $this->Session->check('Item')){
				$this->Session->setFlash('同梱出来る荷物がありません。' , 'default' , array('class' => 'danger'));
				$this->redirect(array('controller' => 'packages' , 'action' => 'arrived'));
			}
			//もしチェックされていなければ
			else {
				$this->Session->setFlash('2つ以上の荷物を選択して下さい。' , 'default' , array('class' => 'danger'));
			}
		}
		
		$params = array(
			//同梱可能で、同梱済みでないものの一覧を表示する
			'conditions' => array(
				'Qrcode.user_id' => $this->Auth->user('id'),
				'Qrcode.is_combine' => 0,
				'Qrcode.is_packaged' => 0,
			),
			'recursive' => -1,
		);
		
		$packages = $this->Qrcode->find('all', $params);
		//対応する荷物がなければリダイレクト
		if(! $packages){
			$this->redirect(array('controller' => 'packages' , 'action' => 'arrived'));
		}
		
		$this->set('packages', $packages);
	}
	
	//引数は配列でも変数でもいいよ！
	protected function __registQrcodeToSession($qrcode_id = null){
		
		//変数が空なら失敗する
		if($qrcode_id == null){
			return false;
		}
		
		$this->Session->delete('Item.qrcode_id');
		
		//変数が配列ならば
		if(is_array($qrcode_id)){
			$this->Session->write('Item.qrcode_id', $qrcode_id);
		}
		//QRコード単体ならば
		else {
			$this->Session->write('Item.qrcode_id.0', $qrcode_id);
		}
		
		return true;
	}
	
	protected function _isCheckedTwoOrMore($id){
		return count($id) > 1 ? true : false;
	}
	
	protected function _formatQrcodeIds($qrcode_ids = null){
		
		if($qrcode_ids == null){
			return array();
		}
		//配列を整形する
		$new_qrcode_ids = array();
		foreach($qrcode_ids as $id){
			array_push($new_qrcode_ids, $id[0]);
		}
		return $new_qrcode_ids;
	}
}