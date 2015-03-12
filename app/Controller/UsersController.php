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
            $this->redirect(array('controller' => '/', 'action' => 'index'));
        }

        if (!empty($this->request->data)) {
            if ($this->Auth->login()) {
                 $this->redirect($this->Auth->redirect());
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
        $this->redirect(array('controller' => '/', 'action' => 'index'));
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
        $user_id = $this->facebook->getUser();

        // 正規ルートでこのページに到着した
        $fb_profile = $this->facebook->api('/me?locale=ja');

        // DBに同じfacebook_user_idがあるかどうか調べる
        $is_facebook_user = $this->User->findByFacebookUserId($user_id, array('id'));

        if ($is_facebook_user) {

            if (!$this->me['is_login']) {

                // ログアウト状態ならログインさせてリダイレクト
                $this->Auth->fields = array(
                    'username' => 'facebook_user_id',
                    'password' => 'facebook_access_token'
                );

                $data['User']['id'] = $is_facebook_user['User']['id'];
                $data['User']['facebook_user_id'] = $user_id;
                $data['User']['facebook_access_token'] = $access_token;

                if ($this->Auth->login($data['User'])) {
                    $this->redirect($this->request->referer()); // $this->Auth->redirect(
                } else {
                    // ログイン失敗
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                }

            } else {
                // 既に同じfacebook_user_idがある場合はトップへ
                $this->redirect(array('controller' => '/', 'action' => 'index'));
            }
        }

        // トランザクション処理
        $this->User->begin();

        // DBに会員情報登録
        $data['User']['facebook_user_id'] = $user_id;
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

}
