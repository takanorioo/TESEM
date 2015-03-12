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
      <a id="test_download" target="_blank"> > Download <img src="/<?php echo $base_dir;?>/img/download.png"></a>
    </span>

  </div>

  <div class="col-md-6">
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

!set <?php echo h($label['Label']['name']);?>.result := <?php echo h($label['Label']['name']);?>.<?php echo h($label['Method']['name']);?>()

    </PRE>
  </div>
</div>



