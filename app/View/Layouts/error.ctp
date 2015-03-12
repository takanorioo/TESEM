<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <?php echo $this->Html->charset(); ?>
    <?php
        echo $this->Html->meta('icon');
        echo $this->Html->css(array('base'));
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
    ?>

</head>
<body>

    <?php echo $this->Session->flash(); ?>

    <?php echo $this->fetch('content'); ?>

</body>
</html>
