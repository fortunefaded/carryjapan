<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');

class IndexController extends AppController {

	public $name = 'Index';
	public $uses = array('User');
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow();
	}
	
	public function index(){
		//タイトル設定
		$this->set('title_for_layout', 'トップページ');
	}
}