<?php
class ImageUpload extends AppModel {
    public $name = 'ImageUpload';
    public $useTable = false;


    // 画像を保存する
    public function imageSave($filepath, $data) {
        $filepath = $this->getRealFilepath($filepath);

        // エラーチェック
        if($data['error'] != 0 || $data['size'] <= 0) {
            return false;
        }

        // 画像を移動
        if(move_uploaded_file($data['tmp_name'], $filepath)) {
            chmod($filepath, 0666);
            return true;
        } else {
            return false;
        }
    }

    // エラーメッセージ
    public function getErrorMessage($data) {
        switch($data['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $message = 'ファイルが大きすぎます';
                break;
            case UPLOAD_ERR_CANT_WRITE:
            case UPLOAD_ERR_PARTIAL:
                $message = 'ファイルが正常にアップロードできませんでした';
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = 'ファイルが選択されていません';
                break;
            default:
                $message = 'エラーが発生しました';
                break;
        }
        return $message;
    }

    // 画像がアップロードされようとしたか
    public function isUpload($data) {
        if($data['error'] == UPLOAD_ERR_NO_FILE) {
            return false;
        }
        return true;
    }

    /* ドキュメントルートからの相対パスを絶対パスに変換 */
    public function getRealFilepath($filepath) {
        $path = $_SERVER['DOCUMENT_ROOT'].DS.$filepath;
        return $path;
    }

}
