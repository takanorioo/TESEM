<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />


    <?php echo $this->Html->charset(); ?>
    <title><?php echo $title_for_layout; ?></title>
    <meta name="copyright" content="copyright(c)2012 SLOGAN All Rights Reserved.">
    <?php echo $this->Html->meta('description', $description_for_layout); ?>
    <?php echo $this->Html->meta('keywords', $keywords_for_layout); ?>

    <?php

        echo $this->Html->css(array('bootstrap-responsive.min.css'));
        echo $this->Html->css(array('bootstrap.min'));
        echo $this->Html->css(array('base'));

        echo $this->fetch('css');

    ?>


</head>
<body style = "padding-top:40px" onload="init()" >

    <?php echo $this->Session->flash(); ?>
    <?php echo $this->fetch('content'); ?>

    </div>
</body>

</html>