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
      <a id="model_download" target="_blank"> > Download <img src="/<?php echo $base_dir;?>/img/download.png"></a>
    </span>

<!-- 準備 -->

<!--  パターン名前-->
<?php $rback_name = ""; ?>
<?php $password_and_use_name = ""; ?>
<?php $rback_method = ""; ?>
<?php $password_and_use_method = ""; ?>


<?php $security_patterns = array(); ?>

<!-- 引数の生成 -->
<?php $argument  = array(); ?>
<?php for($t = 0; $t < count($security_design_requirement); $t++): ?>
  <?php for($p = 0; $p < count($security_design_requirement[$t]['PatternBind']); $p++): ?>
    <?php $argument[] = $security_design_requirement[$t]['PatternBind'][$p]['Label']['name']?>
  <?php endfor; ?>
<?php endfor; ?>
<?php $argument = array_unique($argument); ?>
<?php sort($argument); ?>

<!-- アソシエーションの準備 -->
<?php $right_id = 0; ?>
<?php $user_id = 0; ?>

<!-- 準備終わり -->

    <PRE  class = "modelscript" style="font-size: 10px;display: none;">
      

model Project

-- classes

<?php for($i = 0; $i < count($elements); $i++): ?>
<?php for($t = 0; $t < count($security_design_requirement); $t++): ?>
<?php if($elements[$i]['Label']['id'] == $security_design_requirement[$t]['PatternBind'][0]['Label']['id']): ?>
<!-- RBAC -->
<?php if($security_design_requirement[$t]['Pattern']['id'] == 1): ?>
<?php $security_patterns[] = $elements[$i]['Label']['id']; ?>
<?php $rback_name = $elements[$i]['Label']['name']; ?>
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

operations
  <?php for($j = 0; $j < count($elements[$i]['Method']); $j++): ?><?php echo h($elements[$i]['Method'][$j]['name']);?><?php $rback_method = $elements[$i]['Method'][$j]['name'];?><?php endfor; ?>(<?php echo $security_design_requirement[$t]['PatternBind'][1]['Label']['name']?> : <?php echo $security_design_requirement[$t]['PatternBind'][1]['Label']['name']?>, <?php echo $security_design_requirement[$t]['PatternBind'][2]['Label']['name']?> : <?php echo $security_design_requirement[$t]['PatternBind'][2]['Label']['name']?>, <?php echo $security_design_requirement[$t]['PatternBind'][3]['Label']['name']?> : <?php echo $security_design_requirement[$t]['PatternBind'][3]['Label']['name']?> ) : Boolean = 
  if  <?php echo $security_design_requirement[$t]['PatternBind'][3]['Label']['name']?>.role_id = <?php echo $security_design_requirement[$t]['PatternBind'][1]['Label']['name']?>.id and 
    <?php echo $security_design_requirement[$t]['PatternBind'][1]['Label']['name']?>.id = <?php echo $security_design_requirement[$t]['PatternBind'][2]['Label']['name']?>.role_id and 
    <?php echo $security_design_requirement[$t]['PatternBind'][2]['Label']['name']?>.right = true
    then true
  else false 
  endif
end

<?php endif; ?>

<!-- Password & Use -->
<?php if($security_design_requirement[$t]['Pattern']['id'] == 2): ?>
<?php $security_patterns[] = $elements[$i]['Label']['id']; ?>
<?php $password_and_use_name = $elements[$i]['Label']['name']; ?>
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
  <?php for($j = 0; $j < count($elements[$i]['Method']); $j++): ?><?php $password_and_use_method = $elements[$i]['Method'][$j]['name'];?><?php echo h($elements[$i]['Method'][$j]['name']);?><?php endfor; ?>(<?php echo $security_design_requirement[$t]['PatternBind'][2]['Label']['name']?> : <?php echo $security_design_requirement[$t]['PatternBind'][2]['Label']['name']?>, <?php echo $security_design_requirement[$t]['PatternBind'][3]['Label']['name']?> : <?php echo $security_design_requirement[$t]['PatternBind'][3]['Label']['name']?> ) : Boolean = 
  if 
    <?php echo $security_design_requirement[$t]['PatternBind'][2]['Label']['name']?>.email = <?php echo $security_design_requirement[$t]['PatternBind'][3]['Label']['name']?>.email and 
    <?php echo $security_design_requirement[$t]['PatternBind'][2]['Label']['name']?>.password = <?php echo $security_design_requirement[$t]['PatternBind'][3]['Label']['name']?>.password 
    then true
  else false 
  endif
end
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php endfor; ?>
<?php endfor; ?>


<!-- セキュリティパターン以外 -->
<?php for($i = 0; $i < count($elements); $i++): ?>
<?php if(!in_array($elements[$i]['Label']['id'], $security_patterns)): ?>
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
<!-- ターゲットメソッドの場合 -->
<?php if($elements[$i]['Method'][$j]['id'] == $method['Method']['id']): ?>

delete(<?php for($t = 0; $t < count($argument); $t++): ?><?php echo $argument[$t];?> : <?php echo $argument[$t];?><?php if($t < count($argument) - 1): ?>, <?php endif; ?><?php endfor; ?>) : Boolean =  
  if  
<?php for($t = 0; $t < count($security_design_requirement); $t++): ?>
<?php if($security_design_requirement[$t]['Pattern']['id'] == 1): ?>
    <?php echo h($rback_name);?>.<?php echo h($rback_method);?>(<?php echo $security_design_requirement[$t]['PatternBind'][1]['Label']['name']?>, <?php echo $security_design_requirement[$t]['PatternBind'][2]['Label']['name']?>, <?php echo $security_design_requirement[$t]['PatternBind'][3]['Label']['name']?>) = true <?php if($t < count($security_design_requirement) - 1): ?>and<?php endif; ?> 
<?php endif; ?>
<?php if($security_design_requirement[$t]['Pattern']['id'] == 2): ?>
    <?php echo h($password_and_use_name);?>.<?php echo h($password_and_use_method);?>(<?php echo $security_design_requirement[$t]['PatternBind'][2]['Label']['name']?>, <?php echo $security_design_requirement[$t]['PatternBind'][3]['Label']['name']?>) = true <?php if($t < count($security_design_requirement) - 1): ?>and<?php endif; ?> 
<?php endif; ?>
<?php endfor; ?>
    then true
  else 
    false 
  endif

<?php else: ?>
 <?php echo h($elements[$i]['Method'][$j]['name']);?>() : Boolean = true
<?php endif; ?>
<?php endfor; ?>

end

<?php else: ?>
end

<?php endif; ?>
<?php endif; ?>
<?php endfor; ?>


<?php for($i = 0; $i < count($elements); $i++): ?>
<?php for($t = 0; $t < count($security_design_requirement); $t++): ?>
<!-- RBAC -->
<?php if($elements[$i]['Label']['id'] == $security_design_requirement[$t]['PatternBind'][2]['Label']['id']): ?>
<?php if($security_design_requirement[$t]['Pattern']['id'] == 1): ?>
<?php $right_id = $elements[$i]['Label']['id']; ?>
<?php endif; ?>
<?php endif; ?>
<!-- Password & Use -->
<?php if($elements[$i]['Label']['id'] == $security_design_requirement[$t]['PatternBind'][3]['Label']['id']): ?>
<?php if($security_design_requirement[$t]['Pattern']['id'] == 2): ?>
<?php $user_id = $elements[$i]['Label']['id']; ?>
<?php endif; ?>
<?php endif; ?>
<?php endfor; ?>
<?php endfor; ?>

-- associations

<?php for($i = 0; $i < count($elements); $i++): ?>
<?php for($j = 0; $j < count($elements[$i]['Relation']); $j++)  : ?>
<?php if($elements[$i]['Label']['id'] == $right_id && in_array($elements[$i]['Relation'][$j]['label_relation_id'], $security_patterns)): ?>
association access_<?php echo h($elements[$i]['Relation'][$j]['id'] );?> between
  <?php echo h($elements[$i]['Label']['name']);?>[1..*]
  <?php echo h($elements[$i]['Relation'][$j]['name']);?>[1]
end
<?php elseif($elements[$i]['Label']['id'] == $user_id && in_array($elements[$i]['Relation'][$j]['label_relation_id'], $security_patterns)): ?> 
association access_<?php echo h($elements[$i]['Relation'][$j]['id']);?> between
  <?php echo h($elements[$i]['Label']['name']);?>[1..*]
  <?php echo h($elements[$i]['Relation'][$j]['name']);?>[1]
end
<?php elseif($elements[$i]['Relation'][$j]['label_relation_id'] == $right_id && in_array($elements[$i]['Label']['id'], $security_patterns)): ?> 
association access_<?php echo h($elements[$i]['Relation'][$j]['id']);?> between
  <?php echo h($elements[$i]['Label']['name']);?>[1]
  <?php echo h($elements[$i]['Relation'][$j]['name']);?>[1..*]
end
<?php elseif($elements[$i]['Relation'][$j]['label_relation_id'] == $user_id && in_array($elements[$i]['Label']['id'], $security_patterns)): ?> 
association access_<?php echo h($elements[$i]['Relation'][$j]['id']);?> between
  <?php echo h($elements[$i]['Label']['name']);?>[1]
  <?php echo h($elements[$i]['Relation'][$j]['name']);?>[1..*]
end
<?php else: ?>
association access_<?php echo h($elements[$i]['Relation'][$j]['id']);?> between
  <?php echo h($elements[$i]['Label']['name']);?>[1]
  <?php echo h($elements[$i]['Relation'][$j]['name']);?>[1]
end
<?php endif; ?>
<?php endfor; ?>
<?php endfor; ?>


-- OCL constraints


constraints

<?php for($i = 0; $i < count($security_design_requirement); $i++): ?>

<!-- RBAC -->
<?php if($security_design_requirement[$i]['Pattern']['id'] == 1): ?>
context <?php echo h($label['Label']['name']);?> 
  inv <?php echo h($security_design_requirement[$i]['Pattern']['name']);?> :
    if  self.<?php echo h($security_design_requirement[$i]['PatternBind'][0]['Label']['name']);?>.<?php echo h($security_design_requirement[$i]['PatternBind'][2]['Label']['name']);?>->exists(p | 
        p.right = true and 
        p.role_id = p.<?php echo h($security_design_requirement[$i]['PatternBind'][1]['Label']['name']);?>.id and 
        p.role_id = p.<?php echo h($security_design_requirement[$i]['PatternBind'][1]['Label']['name']);?>.<?php echo h($security_design_requirement[$i]['PatternBind'][3]['Label']['name']);?>.role_id )
  then
    self.<?php for($j = 0; $j < count($behavior_uis); $j++): ?><?php echo h($behavior_uis[$j]['Label']['name']);?><?php endfor; ?>.<?php echo h($behavior_actor['Label']['name']);?>.access_right = true  
  else
    self.<?php for($j = 0; $j < count($behavior_uis); $j++): ?><?php echo h($behavior_uis[$j]['Label']['name']);?><?php endfor; ?>.<?php echo h($behavior_actor['Label']['name']);?>.access_right = false
  endif
<?php endif; ?>

<?php if($security_design_requirement[$i]['Pattern']['id'] == 2): ?>

<!-- Password & Design  -->

context <?php echo h($label['Label']['name']);?> 
  inv <?php echo h($security_design_requirement[$i]['Pattern']['name']);?>:
    if self.<?php echo h($security_design_requirement[$i]['PatternBind'][0]['Label']['name']);?>.<?php echo h($security_design_requirement[$i]['PatternBind'][3]['Label']['name']);?>->exists(p | 
      p.email = self.<?php echo h($security_design_requirement[$i]['PatternBind'][0]['Label']['name']);?>.<?php echo h($security_design_requirement[$i]['PatternBind'][2]['Label']['name']);?>.email and 
      p.password = self.<?php echo h($security_design_requirement[$i]['PatternBind'][0]['Label']['name']);?>.<?php echo h($security_design_requirement[$i]['PatternBind'][2]['Label']['name']);?>.password) 
  then
    self.<?php for($j = 0; $j < count($behavior_uis); $j++): ?><?php echo h($behavior_uis[$j]['Label']['name']);?><?php endfor; ?>.<?php echo h($behavior_actor['Label']['name']);?>.regular_user = true  
  else
    self.<?php for($j = 0; $j < count($behavior_uis); $j++): ?><?php echo h($behavior_uis[$j]['Label']['name']);?><?php endfor; ?>.<?php echo h($behavior_actor['Label']['name']);?>.regular_user = false
  endif 
<?php endif; ?>
<?php endfor; ?>


context <?php echo h($label['Label']['name']);?> 
  inv SecurityDesignRequirments:
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



