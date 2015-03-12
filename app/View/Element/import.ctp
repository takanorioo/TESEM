<form method="post" enctype="multipart/form-data">
  ファイル：<br />
  <input type="file" name="upfile" size="30" /><br />
  <br />
  <input type="submit" value="アップロード" />
  <input type="hidden" name="token" value="<?php echo session_id();?>">
</form>