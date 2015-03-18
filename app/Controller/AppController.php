<?php


App::uses('Controller', 'Controller');

class AppController extends Controller
{
    public $components = array(
        // 'DebugKit.Toolbar',
        'Auth' => array(
            'loginAction' => array(
                'controller' => '/',
                'action' => 'login',
                ),
            'authError' => '認証に失敗しました',
            'authenticate' => array(
                'Form' => array(
                    'fields' => array(
                        'username' => 'email',
                        'password' => 'password'
                        ),
                    )
                )
            ),
        'Cookie',
        'Session',
        'Basic'
        );

    public $me = array();
    public $facebook;
    public $uses = array(
        'Label',
        'Relation',
        'Attribute',
        'Method',
        'Countermeasure',
        'Pattern',
        'Project'
        );
    public $absolute_path_with_protocols;
    public $base_dir;

    function beforeFilter() {

        if($this->viewPath == 'Errors'){
            $this->layout = 'error';
        }


        // 取り敢えず
        $this->set('title_for_layout', '');
        $this->set('keywords_for_layout', '');
        $this->set('description_for_layout', '');

        App::uses('Sanitize', 'Utility');

        /*ユーザー情報埋め込み*/
        $this->me = $this->_getMe();
        $this->set('me', $this->me);


        $this->user = $this->getUser();
        $this->set('user', $this->user);

        /*基本共通変数*/
        $this->base_dir = $this->_getBaseDir();
        $this->set('base_dir', $this->base_dir);

        /*メタ情報*/
        $this->set('copyright', 'copyright(c)2012 SLOGAN All Rights Reserved.');
        $this->set('site_name', 'Goodfind');

        // 定数の取得
        require_once(APP . 'Config' . DS . 'constants.php');

        //もしもPOSTだった場合はトークンが正規のものか確認
        if ($this->request->is('post') && !($this->request->action === 'loginCallback' && $this->request->controller === 'users')) {
            if (!array_key_exists('token', $this->request->data)) {
                throw new BadRequestException();
                //echo "POSTを送信する際はトークンも一緒にhiddenパラメタで送って下さい。name=token, value=session_id()を指定して下さい。";

            }
            if (!$this->Basic->isValidToken($this->request->data['token'])) {
                throw new BadRequestException();
                //echo "POSTを送信する際はトークンも一緒にhiddenパラメタで送って下さい。name=token, value=session_id()を指定して下さい。";
            }
        }

        if (!empty($this->me['is_login'])) {


            $this->set('me', $this->me);

            //ユーザID取得
            $user_id= $this->me['User']['id'];

            // プロジェクト関連処理
            $projects = $this->Project->getProjects($user_id);
            $this->set('projects', $projects);

            $project_id = $this->Session->read('Project.id');

            if(empty($project_id))
            {
                $project_id = 0;
                $project_name = "Please Set Project";
            }

            $project= $this->Project->getProject($project_id);
            $project_name= $project['Project']['name'];
            $this->set('project_name', $project_name);

            
            //要素の読み込み
            $elements = $this->Label->getElements($user_id,$project_id);


            for($i = 0; $i < count($elements); $i++) 
            {
                if(count($elements[$i]['Attribute']) + count($elements[$i]['Method']) > 3) {
                    $num = count($elements[$i]['Attribute']) + count($elements[$i]['Method']);
                    $elements[$i]['height'] = 100 + ($num - 3) * 20 ;
                } else {
                    $elements[$i]['height'] = 100;
                }

            }

            if(!empty($elements))
            {
                for($i = 0; $i < count($elements); $i++) {
                    $relation[$elements[$i]['Label']['id']] = $elements[$i]['Label']['name'];
                }
                $this->set('relation', $relation);

                $relation_key = array_keys($relation);
                for($i = 0; $i < count($relation); $i++) {
                    $option_relation[$i]['id'] = $relation_key[$i];
                    $option_relation[$i]['name'] = $relation[$relation_key[$i]];
                }
                $this->set('option_relation', $option_relation);


                for($i = 0; $i < count($elements); $i++) {
                    $width = 110;
                    $tmp_width = 0;

                    //メソッドの長さを計算

                    for($j = 0; $j < count($elements[$i]['Method']); $j++) {
                        if(!empty($elements[$i]['Method'])) {
                            $methods[$elements[$i]['Method'][$j]['id']] = $elements[$i]['Label']['name']." : ".$elements[$i]['Method'][$j]['name'];
                            if(strlen($elements[$i]['Method'][$j]['name']) > 12) {
                                $num = strlen($elements[$i]['Method'][$j]['name']);
                                $tmp_width = 110 + ($num - 12) * 8 ;
                            }

                        }
                        if($tmp_width > $width) {
                            $width = $tmp_width;
                        }
                    }
                    
                    //ラベル名の長さを計算
                    if(!empty($elements[$i]['Label'])) {
                        if(strlen($elements[$i]['Label']['name']) > 12) {
                            $num = strlen($elements[$i]['Label']['name']);
                            $tmp_width = 110 + ($num - 12) * 8 ;
                        }
                    }
                    if($tmp_width > $width) {
                       $width = $tmp_width;
                    }


                    //インターフェースの長さを計算
                    if(!empty($elements[$i]['Label'])) {;
                        if(strlen($elements[$i]['Label']['interface']) > 12) {
                            $num = strlen($elements[$i]['Label']['interface']);
                            $tmp_width = 110 + ($num - 12) * 8 ;
                        }

                    }
                    if($tmp_width > $width) {
                        $width = $tmp_width;
                    }

                    $elements[$i]['width'] = $width;
                }
                if(!empty($methods)) {
                    $this->set('methods', $methods);
                }
            }
            $this->set('elements', $elements);
        }
    }

    function beforeRender()
    {
            // エラー時に行う特別処理
        if($this->viewPath == 'Errors'){
            $this->layout = 'error';
            $this->set('title_for_layout', '指定されたページは存在しません');

        }
    }


    private function _getMe() {
        return array(
            'is_login' => $this->Auth->loggedIn(),
            'token' => session_id(),
            'User' => $this->getUser()
            );
    }

    protected function createFacebook() {
        return new facebook(array(
            'appId' => FACEBOOK_APP_ID,
            'secret' => FACEBOOK_APP_SECRET,
            'status' => true,
            'oauth' => true,
            'xfbml' => true,
            'cookie' => true
            )
        );
    }

    /*
     * [METHOD] getUser()
     * @description  ログイン状態の時にユーザーの情報を返す
     * @return array $me
     */
    protected function getUser ()
    {
        App::import('Model', 'User');
        $User = new User;

        $me = array();
        if ($this->Auth->loggedIn()) {
            $me = $User->findById($this->Auth->user('id'));

            // Facebook Login
            if(empty($me)) {
                $me = $User->findByFacebookUserId($this->Auth->user('facebook_user_id'));
                return $me['User'];
            }
            return $me['User'];
        }
    }


    private function _getBaseDir() {
        $paths = Router::getPaths();
        return  str_replace('/', '', $paths['base']);
    }


    /*
     * [METHOD] myHash ()
     * @description  メールアドレス関連でハッシュかけるときはコレ使う
     */
    public function myHash($email, $date) {
        return $this->Auth->password($email . $date . 'kobashi');
    }
}
