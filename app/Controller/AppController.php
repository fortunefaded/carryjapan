<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $helpers = array('Html' , 'Form' , 'Session');
	
	public $components = array(
		'Session',
		'Auth' => array(
			'authenticate' => array(
				'Form' => array(
					'fields' => array(
						'username' => 'email',
						'password' => 'password',
					),
					'scope' => array(
						'User.is_active' => 1,
					),
				),
			),
			'loginAction' => array(
				'controller' => 'users',
				'action' => 'login',
			),
			'loginRedirect' => array(
				'controller' => 'mypage',
				'action' => 'index',
			),
			'logoutRedirect' => array(
				'controller' => 'users',
				'action' => 'login',			
			),
		),
    	'DebugKit.Toolbar',
	);
	
    public function beforeFilter(){
		//ユーザー情報を$userとしてctp内で利用できるように
		$this->set('user', $this->Auth->user());
		//ログインステータスのチェック
		if($this->Auth->user()){
			$loggedIn = true;
/*
			$this->loadModel('User');
			$number_of_packages = $this->User->countNumberOfArrivedPackages($this->Auth->user('id'));
			$this->set('number_of_packages', $number_of_packages);
*/
		} else {
			$loggedIn = false;

		}
		$this->set('loggedIn',$loggedIn);
	}

	public function beforeRender(){
		//Compile LESS to CSS
		if(Configure::read('debug') > 0){
			App::import('Vendor', 'lessc');
			$less = ROOT . DS . APP_DIR . DS . 'webroot' . DS . 'less' . DS . 'style.less';
			$css = ROOT . DS . APP_DIR . DS . 'webroot' . DS . 'css' . DS . 'style.css';
			lessc::ccompile($less, $css);
		}
	}
}
