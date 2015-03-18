<script>


  $(document).ready(function(){

    $html = $(".modelscript").text();
    $html = $html.toLowerCase()
    $html = $html.replace(/delete/g,"deletes");
    $html = $html.replace(/date/g,"String");
    $html = $html.replace(/string/g,"String");
    $html = $html.replace(/boolean/g,"Boolean");
    $html = $html.replace(/integer/g,"Integer");
    $(".modelscript").html($html);

    $html = $(".testscript").text();
    $html = $html.replace(/delete/g,"deletes");
    $html = $html.toLowerCase()
    $(".testscript").html($html);
  });

  $(function() {

    $(".modelscript").keyup(function(){
      setBlobModelUrl("model_download", $(".modelscript").text());
    });

    $(".modelscript").keyup(); 

  });

  $(function() {

    $(".testscript").keyup(function(){
      setBlobTestlUrl("test_download", $(".testscript").text());
    });

    $(".testscript").keyup(); 

  });

  function setBlobModelUrl(id, content) {

   // 指定されたデータを保持するBlobを作成する。
   var blob = new Blob([ content ], { "type" : "application/x-msdownload" });
   
   // Aタグのhref属性にBlobオブジェクトを設定する。
   window.URL = window.URL || window.webkitURL;
   $("#" + id).attr("href", window.URL.createObjectURL(blob));
   $("#" + id).attr("download", "model_script.use");
   
 }

 function setBlobTestlUrl(id, content) {

   // 指定されたデータを保持するBlobを作成する。
   var blob = new Blob([ content ], { "type" : "application/x-msdownload" });
   
   // Aタグのhref属性にBlobオブジェクトを設定する。
   window.URL = window.URL || window.webkitURL;
   $("#" + id).attr("href", window.URL.createObjectURL(blob));
   $("#" + id).attr("download", "test_script.txt");
   
 }

</script>


<div class="row">
  <div class="col-md-12 ">
    <sapn style="font-size: 30px;">Model Script (.use)
      <a id="model_download" target="_blank"> > Download <img src="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/img/download.png"></a>
    </span>
    <PRE  class = "modelscript" style="font-size: 10px;display: none;">
      

model Project

-- classes

<?php for($i = 0; $i < count($elements); $i++): ?>
class <?php echo h($elements[$i]['Label']['name']);?>


<?php if(!empty($elements[$i]['Attribute']) || ($elements[$i]['Label']['id'] == $label['Label']['id'])): ?>
attributes
<?php if($elements[$i]['Label']['id'] == $label['Label']['id']): ?>
  result : Boolean
<?php endif; ?>
<?php for($j = 0; $j < count($elements[$i]['Attribute']); $j++): ?>
 <?php echo h($elements[$i]['Attribute'][$j]['name']);?> : <?php echo h($TYPE[$elements[$i]['Attribute'][$j]['type']]);?>

<?php endfor; ?>
<?php endif; ?>


<?php if(!empty($elements[$i]['Method'])): ?>
operations

<?php for($j = 0; $j < count($elements[$i]['Method']); $j++): ?>
 <?php echo h($elements[$i]['Method'][$j]['name']);?>() : Boolean = true
<?php endfor; ?>

end
<?php else: ?>
end
<?php endif; ?>

<?php endfor; ?>


-- associations

<?php for($i = 0; $i < count($elements); $i++): ?>
<?php for($j = 0; $j < count($elements[$i]['Relation']); $j++)  : ?>
association access_<?php echo h($elements[$i]['Relation'][$j]['id']);?> between
  <?php echo h($elements[$i]['Label']['name']);?>[1]
  <?php echo h($elements[$i]['Relation'][$j]['name']);?>[1]
end
<?php endfor; ?>
<?php endfor; ?>


-- OCL constraints


constraints

context <?php echo h($label['Label']['name']);?>  
  inv <?php echo h($label['Label']['name']);?>   :
    if 
<?php for($i = 0; $i < count($security_requirement); $i++): ?>
<?php if($security_requirement[$i]['Countermeasure']['id'] == 1): ?>
      self.<?php for($j = 0; $j < count($behavior_uis); $j++): ?><?php echo h($behavior_uis[$j]['Label']['name']);?><?php endfor; ?>.<?php echo h($behavior_actor['Label']['name']);?>.regular_user = true 
<?php if($i != $security_requirement_count - 1): ?>
        and
<?php endif; ?>
<?php endif; ?>
<?php if($security_requirement[$i]['Countermeasure']['id'] == 2): ?>
      self.<?php for($j = 0; $j < count($behavior_uis); $j++): ?><?php echo h($behavior_uis[$j]['Label']['name']);?><?php endfor; ?>.<?php echo h($behavior_actor['Label']['name']);?>.access_right = true 
<?php if($i != $security_requirement_count - 1): ?>
        and
<?php endif; ?>
<?php endif; ?>
<?php endfor; ?>
    then
      self.result = true
    else
      self.result = false
  endif   

    </PRE>
  </div>
</div>



