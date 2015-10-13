<?php
App::uses('AppController','Controller');

class MypageController extends AppController {

	public $name = 'Mypage';
	public $uses = array('User', 'UserAddress');
	
	public function beforeFilter(){
		parent::beforeFilter();
	}
	
	public function index(){

	}
	
	public function setting(){
		
	}
	
	public function deliver(){
		
		$params = array(
			'conditions' => array(
				'UserAddress.user_id' => $this->Auth->user('id'),
			),
		);
	
		$addresses = $this->UserAddress->find('all', $params);
	
		$this->set('addresses', $addresses);
	
		if($this->request->is('post')){
			if(! $this->UserAddress->invalidFields()){
				$this->request->data['UserAddress']['user_id'] = $this->Auth->user('id');
				//登録の結果の通知を出し、
				if($this->UserAddress->save($this->request->data)){
					$this->Session->setFlash('データの保存に成功しました。' , 'default' , array('class' => 'danger'));
				} else {
					$this->Session->setFlash('データの保存に失敗しました。' , 'default' , array('class' => 'danger'));
				}
				//マイページへリダイレクト
				$this->redirect(array('controller' => 'mypage' , 'action' => 'deliver'));
			}
		}
	}
}