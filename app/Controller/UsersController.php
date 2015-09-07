<?php
/**
 * UsersController
 *
 * @author        Takanori Kobashi kobashi@akane.waseda.jp
 * @since         1.0.0
 * @version       1.0.0
 * @copyright
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::import('Vendor', 'facebook/facebook');

class UsersController extends AppController
{
    public $name = 'Users';
    public $uses = array('User');
    public $layout = 'login';
    public $components = array('ImageUpload');

    /**
     * beforeFilter
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(); //認証なしで入れるページ
        $this->Auth->deny('profile');
	$this->set("title_for_layout","TESEM");
    }

     /**
     * logout ログアウトページ
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
     public function login()
     {
        // ログイン状態ならトップへ
        if ($this->me['is_login']) {
            $this->redirect(array('controller' => 'Projects', 'action' => 'index'));
        }

        if (!empty($this->request->data)) {
            if ($this->Auth->login()) {
               $this->redirect(array('controller' => 'Projects', 'action' => 'index'));
           } else {
            if (isset($this->request->data['User']['email']) && isset($this->request->data['User']['password'])) {
                $this->User->invalidate('login', 'メールアドレスとパスワードの組み合わせが間違っています。');
            }
        }
    }
}

    /**
     * facebook Facebookログイン前処理ページ
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function facebook()
    {
        $facebook = $this->createFacebook();
        $option = array(
            'redirect_uri' => HTTPS_FULL_BASE_URL . '/' . $this->base_dir . '/login/facebook',
            );
        $url = $facebook->getLoginUrl($option);
        $this->redirect($url);
    }

    /**
     * loginFacebook Facebookログイン用メソッド
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function loginFacebook()
    {
        $this->facebook = $this->createFacebook();
        // ログイン状態ならトップへ
        if ($this->me['is_login']) {
            $this->redirect(array('controller' => '/', 'action' => 'index'));
        }

        // facebookの認証チェック
        $facebook_user_id = $this->facebook->getUser();
        if (!$facebook_user_id) {
            // facebook情報の取得が出来なかったので失敗。トップへ
            $this->redirect(array('controller' => '/', 'action' => 'register'));
        }

        // facebookのuser_idからGfのuser_idを逆引き
        $user_id = $this->User->findByFacebookUserId($facebook_user_id, array('id'));

        if (empty($user_id)) {
            // facebook_idからuser_idへの逆引き失敗
            $this->redirect(array('controller' => '/', 'action' => 'register'));
        }

        $data['User']['id'] = $user_id['User']['id'];
        $data['User']['facebook_user_id'] = $facebook_user_id;
        $data['User']['facebook_access_token'] = $this->facebook->getAccessToken();

        // トランザクション処理
        $this->User->begin();
        if (! $this->User->save($data['User'], true, array('id', 'facebook_connect', 'facebook_user_id', 'facebook_access_token'))) {
            $this->User->rollback();
            throw new InternalErrorException();
        }

        $this->User->commit();
        $this->Auth->fields = array(
            'username' => 'facebook_user_id',
            'password' => 'facebook_access_token'
            );
        // ログイン処理
        if ($this->Auth->login($data['User'])) {

            $this->redirect($this->request->referer());
        } else {
            // ログイン失敗
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
    }

    /**
     * logout ログアウトページ
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function logout()
    {
        //ログアウトの処理
        if($this->me['is_login']) {
            $this->Auth->logout();
            $this->Session->destroy();
            $this->redirect($this->request->referer()); // 元いたページにリダイレクト
        }

        $this->Auth->logout();
        $this->Session->destroy();
        $this->redirect(array('controller' => '/', 'action' => 'login'));
    }

    /**
     * logout ログアウトページ
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function sign_up()
    {
     if (!empty($this->request->data)) {



        // トランザクション処理
        $this->User->begin();

        // DBに会員情報登録
        $data['User']['email'] = $this->request->data['User']['email'];
        $data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);
        $data['User']['first_name'] = $this->request->data['User']['first_name'];
        $data['User']['last_name'] = $this->request->data['User']['last_name'];
        $data['User']['user_name'] = $this->request->data['User']['user_name'];

        $this->User->create();
        if (! $this->User->save($data['User'], true, array('email', 'password', 'first_name', 'last_name','user_name'))) {
            $this->User->rollback();
            $this->User->invalidate('DB', 'ただいまサーバーが混み合っております。時間をおいてからアクセスしてください。');
            return;
        }

        $user_id = $this->User->id;

        if( is_uploaded_file($this->data['Upload']['file_name']['tmp_name']) ){

                $path_parts = pathinfo($this->data['Upload']['file_name']['name']);

                if (!($path_parts['extension'] == 'jpg' || $path_parts['extension'] == 'png')) {
                    $this->Session->setFlash("拡張子はjpgかpngでアップロードしてください。");
                    $this->Post->rollback();
                    $this->redirect($this->referer());
                }

                if($this->data['Upload']['file_name']['size'] > 1024*1024){//1024=1KB
                    $this->Session->setFlash("画像は1KBまでです！");
                    $this->redirect($this->referer());
                }

                //アップロードするファイルの場所
                $uploaddir = "img/user";
                $name = 'user_'.$user_id.'.jpg';
                $uploadfile = $uploaddir.DS.$name;

                //画像をテンポラリーの場所から、正式な置き場所へ移動
                if (move_uploaded_file($this->data['Upload']['file_name']['tmp_name'], $uploadfile)){

                    chmod($uploadfile, 0666);

                } else {
                    //失敗
                    $this->Session->setFlash("ファイルのアップロードに失敗しました。");
                    $this->redirect($this->referer());

                }

                //画像のリサイズ
                $this->ImageUpload->reUserThumbnail($user_id);

            } else {
                $this->Session->setFlash('Please upload your profile image', 'default', array('class' => 'alert alert-danger'));
                $this->User->rollback();
                $this->redirect($this->referer());
            }



        $this->User->commit();
        // トランザクション処理終わり

        $this->Session->setFlash('You successfully sign_up! Please Login!', 'default', array('class' => 'alert alert-success'));
        $this->redirect(array('controller' => '/', 'action' => 'login'));

    }
}


    /**
     * loginCallback Facebook Register後コールバック
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function loginCallback()
    {
        $this->facebook = $this->createFacebook();

        $access_token = $this->facebook->getAccessToken();
        $facebook_user_id = $this->facebook->getUser();

        // 正規ルートでこのページに到着した
        $fb_profile = $this->facebook->api('/me?locale=ja');

        // DBに同じfacebook_user_idがあるかどうか調べる
        $is_facebook_user = $this->User->findByFacebookUserId($facebook_user_id, array('id'));

        if ($is_facebook_user) {

            if (!$this->me['is_login']) {

                // ログアウト状態ならログインさせてリダイレクト
                $this->Auth->fields = array(
                    'username' => 'facebook_user_id',
                    'password' => 'facebook_access_token'
                    );

                $data['User']['id'] = $is_facebook_user['User']['id'];
                $data['User']['facebook_user_id'] = $facebook_user_id;
                $data['User']['facebook_access_token'] = $access_token;

                if ($this->Auth->login($data['User'])) {
                    $this->redirect($this->request->referer()); // $this->Auth->redirect(
                } else {
                    // ログイン失敗
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                }

            } else {
                // 既に同じfacebook_user_idがある場合はトップへ
                $this->redirect(array('controller' => 'Projects', 'action' => 'index'));
            }
        }

        // トランザクション処理
        $this->User->begin();

        // DBに会員情報登録
        $data['User']['facebook_user_id'] = $facebook_user_id;
        $data['User']['facebook_access_token'] = $access_token;
        $data['User']['first_name'] = $fb_profile['first_name'];
        $data['User']['last_name'] = $fb_profile['last_name'];

        $this->User->create();
        if (! $this->User->save($data['User'], true, array('first_name', 'last_name', 'facebook_user_id', 'facebook_access_token'))) {
            $this->User->rollback();
            $this->User->invalidate('DB', 'ただいまサーバーが混み合っております。時間をおいてからアクセスしてください。');
            return;
        }
        $user_id = $this->User->id;

        //画像保存処理
        $url = 'https://graph.facebook.com/'.$facebook_user_id.'/picture?width=500';
        $url_data = file_get_contents($url);
        file_put_contents('img/user/user_'.$user_id.'.jpg',$url_data);
        chmod('img/user/user_'.$user_id.'.jpg', 0666);

        //画像のリサイズ
        $this->ImageUpload->reUserThumbnail($user_id);

        $this->User->commit();
        // トランザクション処理終わり

        if ($this->me['is_login']) {

            $this->redirect(array('controller' => '/', 'action' => 'index'));

        } else {
            // ログアウト状態ならログインさせてリダイレクト
            $this->Auth->fields = array(
                'username' => 'facebook_user_id',
                'password' => 'facebook_access_token'
                );
            if ($this->Auth->login($data['User'])) {

                $this->redirect($this->Auth->redirect());
            } else {
                // ログイン失敗
                $this->redirect(array('controller' => '/', 'action' => 'login'));
            }
        }
    }

    /**
     * register
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function register()
    {
        if ($this->me['is_login']) {
            $this->redirect('/');
        }

        $facebook = $this->createFacebook();

        $option = array(
            'redirect_uri' => HTTPS_FULL_BASE_URL . '/' . $this->base_dir . '/login/callback',
            );

        $url = $facebook->getLoginUrl($option);
        $this->redirect($url);
    }

    /**
     * profile
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function profile()
    {
        $this->layout = 'base';

        $allow_non_project = true;
        $this->set('allow_non_project', $allow_non_project);

        if (!empty($this->request->data)) {

            $user_id = $this->me['User']['id'];

            if( is_uploaded_file($this->data['Upload']['file_name']['tmp_name']) ){

                $path_parts = pathinfo($this->data['Upload']['file_name']['name']);

                if (!($path_parts['extension'] == 'jpg' || $path_parts['extension'] == 'png')) {
                    $this->Session->setFlash("拡張子はjpgかpngでアップロードしてください。");
                    $this->Post->rollback();
                    $this->redirect($this->referer());
                }

                if($this->data['Upload']['file_name']['size'] > 1024*1024){//1024=1KB
                    $this->Session->setFlash("画像は1KBまでです！");
                    $this->redirect($this->referer());
                }

                //アップロードするファイルの場所
                $uploaddir = "img/user";
                $name = 'user_'.$user_id.'.jpg';
                $uploadfile = $uploaddir.DS.$name;

                //画像をテンポラリーの場所から、正式な置き場所へ移動
                if (move_uploaded_file($this->data['Upload']['file_name']['tmp_name'], $uploadfile)){

                    chmod($uploadfile, 0666);

                } else {
                    //失敗
                    $this->Session->setFlash("ファイルのアップロードに失敗しました。");
                    $this->redirect($this->referer());

                }

                //画像のリサイズ
                $this->ImageUpload->reUserThumbnail($user_id);

            }

            // トランザクション処理
            $this->User->begin();

            // DBに会員情報登録
            $data['User']['id'] = $this->me['User']['id'];
            $data['User']['first_name'] = $this->request->data['User']['first_name'];
            $data['User']['last_name'] = $this->request->data['User']['last_name'];
            $data['User']['user_name'] = $this->request->data['User']['user_name'];

            $this->User->create();
            if (! $this->User->save($data['User'], true, array('id','first_name', 'last_name','user_name'))) {
                $this->User->rollback();
                $this->User->invalidate('DB', 'ただいまサーバーが混み合っております。時間をおいてからアクセスしてください。');
                return;
            }

            $this->User->commit();
            // トランザクション処理終わり

            $this->Session->setFlash('You successfully edit your profile', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => '/', 'action' => 'profile'));

        }
    }
}
