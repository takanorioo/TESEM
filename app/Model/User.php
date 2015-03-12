<?php
/* User Model
 *
 */
class User extends AppModel {
    public $name = 'User';


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

}
