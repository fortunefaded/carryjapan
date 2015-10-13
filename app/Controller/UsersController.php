<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');

class UsersController extends AppController {

	public $name = 'Users';
	public $uses = 'User';
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow();
	}
	
	public function login(){
		//タイトル設定
		$this->set('title_for_layout', 'ログイン');
		if($this->request->is('post')){
			if($this->Auth->login()){
				$this->redirect(array('controller' => 'index' , 'action' => 'index'));
			} else {
				$is_active = $this->User->field('User.is_active', array('email' => $this->data['User']['email']));
				if($is_active == 0){
					$this->Session->setFlash('本登録が完了していません。' , 'default' , array('class' => 'danger'));
				} else {
					$this->Session->setFlash('ユーザー名かパスワードが違います。' , 'default' , array('class' => 'danger'));
				}
			}
		}
	}
	
	public function logout(){
		//タイトル設定
		$this->set('title_for_layout', 'ログアウト');
		$this->Auth->logout();
		$this->redirect(array('controller' => 'users' , 'action' => 'login'));
	}
	
	public function signup(){
		//タイトル設定
		$this->set('title_for_layout', '会員登録');
		//リクエストが飛んでない場合ビューを表示
		if(! $this->request->data){
			$this->render();
			return;
		}

		//バリデーションをかける
		$this->User->set($this->request->data);
		$this->User->validate = array(
			'email' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'メールアドレスを入力してください。'
				),
				array(
					'rule' => 'email',
					'message' =>'メールアドレスの形式で入力して下さい。'
				),
				array(
					'rule' => 'isUnique',
					'message' =>'すでに使用されているメールアドレスです。'
				),
			),
			'password' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'パスワードを入力してください。'
				),
				array(
					'rule' => array('minLength' , 8),
					'message' => 'パスワードは8文字以上で入力してください。'
				),
				array(
					'rule' => 'alphaNumeric',
					'message' =>'パスワードは英数字で入力してください。'
				),
				array(
					'rule' => 'confirm',
					'message' =>'パスワードが一致していません。'
				),
			),
		);
		
		//バリデーションに通ったら
		if(! $this->User->invalidFields()){
			//入力データを保持
			$email = $this->request->data['User']['email'];
			//現時刻からコードを生成
			$activation_code = md5($email.time());
	
			//条件にあうユーザーをみつけてくる
			$user = $this->User->find('first',array(
				'conditions' => array(
					'email' => $email,
					'is_active' => false,
				)
			));
	
			//nullだったらつくる
			if(! $user){
				$this->User->create();
				$user = array('User' => $this->request->data['User']);
			}
			//アクティブ状態オフ
			$user['User']['is_active'] = false;
			//アクティベーションコードを登録
			$user['User']['activation_code'] = $activation_code;

			//仮データを保存
			$this->User->save($user);
			
			//メールを送る
			$cakeemail = new CakeEmail('signup');
			$cakeemail->to($email);
			$cakeemail->from ('signup@carryjapan.info');
			$cakeemail->subject('Carry Japan本登録のお願い');
			$cakeemail->send(sprintf(
				HOME_URL . '/users/activate/%s',$activation_code
			));
			//送信完了画面を表示
			$this->redirect(array('action' => 'users' , 'action' => 'sentmail'));
		}
	}
	
	public function sentmail(){
		//タイトル設定
		$this->set('title_for_layout', '会員登録用のメールをお送りしました');
	}
	
	public function activate($activation_code){
		//タイトル設定
		$this->set('title_for_layout', '会員本登録');
		//アクティベーション用コードが発行されていて、且つアカウントがアクティベートされていないことを確認
		$user = $this->User->find(
			'first',
			array(
				'conditions' => array(
					'activation_code' => $activation_code,
					'is_active' => false
				)
			)
		);

		//上記検索条件にマッチした情報がない場合、リダイレクト
		if(! $user){
			$this->redirect(array('controller' => 'users' , 'action' => 'signup'));
		}

		//ユーザーをアクティブ状態にして
		$user['User']['is_active'] = true;
		//ユニークコードを生成し
		$user['User']['unique_id'] = $this->__createUniqueCode($user['User']['id']);
		$this->User->save($user);
	
		//ログイン
		$this->Auth->login($user['User']);
		//メッセージ持たせてリダイレクト
		$this->Session->setFlash('会員登録が完了しました。' , 'default' , array('class' => 'success'));
		$this->redirect(array('controller' => 'index' , 'action' => 'index'));
	}
	
	public function reissue(){
		//タイトル設定
		$this->set('title_for_layout', 'パスワードの再設定');
		//リクエストが飛んでない場合ビューを表示
		if(! $this->request->data){
			$this->render();
			return;
		}

		//バリデーションをかける
		$this->User->set($this->request->data);
		$this->User->validate = array(
			'email' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'メールアドレスを入力してください。'
				),
				array(
					'rule' => 'email',
					'message' =>'メールアドレスの形式で入力して下さい。'
				),
			),
		);
		
		//バリデーションに通ったら
		if(! $this->User->invalidFields()){
			//条件にあうユーザーをみつけてくる
			$email = $this->request->data['User']['email'];
			
			$user = $this->User->find('first', array(
				'conditions' => array(
					'email' => $email,
				)
			));
			
			//該当ユーザー居なければ即return,リソースもったいない
			if(! $user){
				$this->render('reissued');
				return;
			}
			
			//いればそいつにメールを送る
			$cakeemail = new CakeEmail('signup');
			$cakeemail->to($email);
			$cakeemail->from ('noreply@carryjapan.info');
			$cakeemail->subject('パスワード再設定のご案内');
			$cakeemail->send(sprintf(
				HOME_URL . '/users/resetting/%s',$user['User']['activation_code']
			));
			$this->render('reissued');
			return;
		}
	}
	
	public function resetting($activation_code){
		//タイトル設定
		$this->set('title_for_layout', 'パスワードの再設定');
		$params = array(
			'conditions' => array(
				'User.activation_code' => $activation_code,
			),
		);
		$user = $this->User->find('first', $params);
		//該当ユーザーが居なければリダイレクトする
		if(! $user){
			$this->redirect(array('controller' => 'index' , 'action' => 'index'));
		}

		//リクエストが飛んでない場合ビューを表示
		if(! $this->request->data){
			$this->render();
			return;
		}		
				
		$this->User->set($this->request->data);
		$this->User->validate = array(
			'password' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'パスワードを入力してください。'
				),
				array(
					'rule' => array('minLength' , 8),
					'message' => 'パスワードは8文字以上で入力してください。'
				),
				array(
					'rule' => 'alphaNumeric',
					'message' =>'パスワードは英数字で入力してください。'
				),
				array(
					'rule' => 'confirm',
					'message' =>'パスワードが一致していません。'
				),
			),
		);
		
		//バリデーションに通ったら
		if(! $this->User->invalidFields()){
			unset($this->request->data['User']['password_confirm']);
			$this->request->data['User']['id'] = $user['User']['id'];
			if($this->User->save($this->request->data)){
				//メッセージ持たせてリダイレクト
				$this->Session->setFlash('パスワードの再設定が完了しました。' , 'default' , array('class' => 'success'));
				$this->redirect(array('controller' => 'index' , 'action' => 'index'));
			}
		}
	}
	
	private function __createUniqueCode($user_id){
	
		$alphabets = array('A','B','C','D','E','F','G','H','J','K');
		$user_unique_id = '';
		$number_of_digits = 7;
		
		//9ずつ増やす
		$user_id = $user_id * 9;
		//必要桁数まで0で埋める
		$user_id = str_pad($user_id, $number_of_digits, '0', STR_PAD_LEFT);
		
		for($i=0; $i<$number_of_digits; $i++){
			if($i < 3/*頭三文字目までアルファベット*/){
				$user_unique_id .= $alphabets[$user_id[$i]];
			} else {
				$user_unique_id .= $user_id[$i];}
		}
		
		return $user_unique_id;
	}
}