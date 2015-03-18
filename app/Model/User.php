<?php
/* User Model
 *
 */
class User extends AppModel {
    public $name = 'User';
    public $validate = array(
        'email' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Enter your email'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This email is alredy registerd'
            ),
        ),
        'password' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Enter your password'
            ),
        ),
        'user_name' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This user name is alredy registerd'
            ),
        ),
    );


    //ユーザーIDからユーザー情報を取得
    function getUserInfo($user_id){
        $result = $this->findById($user_id);
        $num = $this->getNumRows();
        if($num > 0){
            return $result;
        } else {
            return 'GET_USER_INFO_NO_RECORD_ERROR';
        }
    }

    //myAlbumユーザのFacebookFriend情報を取得
    public function getFriendInfo($facebook_friend_id) {

        $result = $this->find('all', array(
            'conditions' => array(
                'User.facebook_user_id' => $facebook_friend_id,
            ),
            'joins' => array(
                array(
                    'type' => 'LEFT',
                    'table' => 'menterings',
                    'alias' => 'Mentering',
                    // 'conditions' => "User.id = Mentering.user_id"
                    'conditions' => array(
                            'User.id = Mentering.user_id',
                            'Mentering.album_id' => '1',
                        )
                )
            ),
            'fields' => array("User.*","Mentering.id","Mentering.is_valid")
        ));
        return $result;
    }

    //ユーザーIDからユーザー情報を取得
    function getUserInfoByUserName($user_name){

        $result = $this->find('first', array(
            'conditions' => array(
                'User.user_name' => $user_name,
            )
        ));

        return $result['User']['id'];
    }

}
