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
    <br>
    <sapn style="font-size: 30px;">Test Script (.txt)
      <a id="test_download" target="_blank"> > Download <img src="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/img/download.png"></a>
    </span>

  </div>

  <div class="col-md-6">


<!--  パターン名前-->
<?php $rback_method = ""; ?>
<?php $password_and_use_method = ""; ?>


<!-- 引数の生成 -->
<?php $argument  = array(); ?>
<?php for($t = 0; $t < count($security_design_requirement); $t++): ?>
  <?php for($p = 0; $p < count($security_design_requirement[$t]['PatternBind']); $p++): ?>
    <?php $argument[] = $security_design_requirement[$t]['PatternBind'][$p]['Label']['name']?>
  <?php endfor; ?>
<?php endfor; ?>
<?php $argument = array_unique($argument); ?>
<?php sort($argument); ?>


    <PRE class = "testscript" style="font-size: 10px;display: none;">

-- Test Case

-- Create instances

<?php for($i = 0; $i < count($elements); $i++): ?>
 !create <?php echo h($elements[$i]['Label']['name']);?> : <?php echo h($elements[$i]['Label']['name']);?>

<?php endfor; ?>


-- Insert associations

<?php for($i = 0; $i < count($elements); $i++): ?>
<?php for($j = 0; $j < count($elements[$i]['Relation']); $j++)  : ?>
 !insert (<?php echo h($elements[$i]['Label']['name']);?>, <?php echo h($elements[$i]['Relation'][$j]['name']);?>) into access_<?php echo h($elements[$i]['Relation'][$j]['id']);?>

<?php endfor; ?>
<?php endfor; ?>



-- Set Test Case

<?php  $attributes_key = array_keys($attributes); ?>
<?php for($i = 0; $i < count($attributes); $i++): ?>
 !set <?php echo h($attributes[$attributes_key[$i]]['name']);?>.<?php echo h($attributes[$attributes_key[$i]]['attribute_name']);?> :=<?php if(!empty($attributes[$attributes_key[$i]]['testcase'])): ?><?php if(is_numeric($attributes[$attributes_key[$i]]['testcase']) || $attributes[$attributes_key[$i]]['testcase'] == 'true' || $attributes[$attributes_key[$i]]['testcase'] == 'false'): ?><?php echo h($attributes[$attributes_key[$i]]['testcase']);?><?php else: ?>'<?php echo h($attributes[$attributes_key[$i]]['testcase']);?>'
<?php endif; ?>
<?php endif; ?>

<?php endfor; ?>


-- Execute Method

<?php for($i = 0; $i < count($elements); $i++): ?>
<?php for($t = 0; $t < count($security_design_requirement); $t++): ?>
<?php if($elements[$i]['Label']['id'] == $security_design_requirement[$t]['PatternBind'][0]['Label']['id']): ?>
<!-- RBAC -->
<?php if($security_design_requirement[$t]['Pattern']['id'] == 1): ?>
!set administrator.access_right := <?php echo $elements[$i]['Label']['name']; ?>.<?php for($j = 0; $j < count($elements[$i]['Method']); $j++): ?><?php echo h($elements[$i]['Method'][$j]['name']);?><?php $rback_method = $elements[$i]['Method'][$j]['name'];?><?php endfor; ?>(<?php echo $security_design_requirement[$t]['PatternBind'][1]['Label']['name']?>,<?php echo $security_design_requirement[$t]['PatternBind'][2]['Label']['name']?>,<?php echo $security_design_requirement[$t]['PatternBind'][3]['Label']['name']?>)
<?php endif; ?>

<!-- Password & Use -->
<?php if($security_design_requirement[$t]['Pattern']['id'] == 2): ?>
<?php if(!empty($elements[$i]['Method'])): ?>

!set administrator.regular_user := <?php echo $elements[$i]['Label']['name']; ?>.<?php for($j = 0; $j < count($elements[$i]['Method']); $j++): ?><?php $password_and_use_method = $elements[$i]['Method'][$j]['name'];?><?php echo h($elements[$i]['Method'][$j]['name']);?><?php endfor; ?>(<?php echo $security_design_requirement[$t]['PatternBind'][2]['Label']['name']?>,<?php echo $security_design_requirement[$t]['PatternBind'][3]['Label']['name']?> )
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php endfor; ?>
<?php endfor; ?>

!set <?php echo h($label['Label']['name']);?>.result := <?php echo h($label['Label']['name']);?>.<?php echo h($label['Method']['name']);?>(<?php for($t = 0; $t < count($argument); $t++): ?><?php echo $argument[$t];?><?php if($t < count($argument) - 1): ?>,<?php endif; ?><?php endfor; ?>)
    </PRE>
  </div>
</div>



