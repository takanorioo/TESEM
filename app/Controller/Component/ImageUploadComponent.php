<?php

require_once 'class.image.php';

class ImageUploadComponent extends Component {


    public function reUserThumbnail($user_id) {

        list($width,$height)=getimagesize(Configure::read('URL').'img/user/user_'.$user_id.'.jpg');

        $rate = $height / $width;

        $rewidth = 300;
        $reheight = 300 * $rate;

        $imgurl  = Configure::read('URL').'img/user/';
        $imgname     = 'user_'.$user_id.'.jpg';
        $new_imgname = 'user_'.$user_id.'.jpg';

        $thumb = new Image($imgurl . $imgname);
        $thumb->name($new_imgname);

        $thumb->width($rewidth);
        $thumb->height($reheight);
        $thumb->save();

        //画像を正方形に
        $thumb = new Image($imgurl . $imgname);
        $thumb->name($new_imgname);

        //もし縦の方が長い場合
        if ($rate > 1) {
            $diff = $reheight - $rewidth ;
            $thumb->crop(0,$diff/2);
            $thumb->width($rewidth);
            $thumb->height($reheight-$diff);
        } else {
            $diff = $rewidth - $reheight ;
            $thumb->crop($diff/2,0);
            $thumb->width($rewidth-$diff);
            $thumb->height($reheight);
        }

        $thumb->save();

        //サムネイル生成
        $imgurl  = Configure::read('URL').'img/user/';
        $imgname     = 'user_'.$user_id.'.jpg';
        $new_imgname = 'user_thumbnail/user_thumbnail_'.$user_id.'.jpg';

        $thumb = new Image($imgurl . $imgname);
        $thumb->name($new_imgname);

        $thumb->resize(50);
        $thumb->save();

    }

    public function rePostImg($post_id) {

        list($width,$height)=getimagesize("img/post/post_".$post_id.".jpg");

        $rate = $height / $width;
        $rewidth = 400;
        $reheight = 400 * $rate;

        $src=@imagecreatefromjpeg("img/post/post_".$post_id.".jpg");
        $dst=imagecreatetruecolor($rewidth,$reheight);

        imagecopyresized($dst,$src,0,0,0,0,$rewidth,$reheight,$width,$height);
        imagejpeg($dst,"img/post/post_".$post_id.".jpg");
    }

    public function reAlbumImg($album_id) {

        list($width,$height)=getimagesize("img/album/album_".$album_id.".jpg");

        $rate = $height / $width;

        $rewidth = 300;
        $reheight = 300 * $rate;

        $imgurl  = 'img/album/';
        $imgname     = 'album_'.$album_id.'.jpg';
        $new_imgname = 'album_'.$album_id.'.jpg';

        $thumb = new Image($imgurl . $imgname);
        $thumb->name($new_imgname);

        $thumb->width($rewidth);
        $thumb->height($reheight);
        $thumb->save();

        //画像を正方形に
        $thumb = new Image($imgurl . $imgname);
        $thumb->name($new_imgname);

        //もし縦の方が長い場合
        if ($rate > 1) {
            $diff = $reheight - $rewidth ;
            $thumb->crop(0,$diff/2);
            $thumb->width($rewidth);
            $thumb->height($reheight-$diff);
        } else {
            $diff = $rewidth - $reheight ;
            $thumb->crop($diff/2,0);
            $thumb->width($rewidth-$diff);
            $thumb->height($reheight);
        }

        $thumb->save();
    }



}
