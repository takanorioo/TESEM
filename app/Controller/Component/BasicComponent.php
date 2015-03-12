<?php
class BasicComponent extends Component {
    /*
     * tokenが正しいかどうか判定するメソッド
     * @param: $token  フォームにセットされたトークン
     * @return boolean 結果
     */
    public function isValidToken($token = null) {
        if ($token === session_id()) {
            return true;
        } else {
            return false;
        }
    }

    public function getDateFormat ($date, $option) {
        return str_replace(array('(0)','(1)','(2)','(3)','(4)','(5)','(6)'), array('(日)','(月)','(火)','(水)','(木)','(金)','(土)'), date($option, strtotime($date)));
    }
}
