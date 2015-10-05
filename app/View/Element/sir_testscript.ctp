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

  $(function() {

    $(".ajscript").keyup(function(){
      setBlobAjUrl("aj_download", $(".ajscript").text());
    });

    $(".ajscript").keyup(); 

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
   $("#" + id).attr("download", "test_script.java");
   
 }

 function setBlobAjUrl(id, content) {

   // 指定されたデータを保持するBlobを作成する。
   var blob = new Blob([ content ], { "type" : "application/x-msdownload" });
   
   // Aタグのhref属性にBlobオブジェクトを設定する。
   window.URL = window.URL || window.webkitURL;
   $("#" + id).attr("href", window.URL.createObjectURL(blob));
   $("#" + id).attr("download", "aspectj.aj");
   
 }


</script>


<div class="row">

  <div class="col-md-12 ">
    <br>
    <sapn style="font-size: 30px;">Test Script (.java)
      <a id="test_download" target="_blank"> > Download <img src="<?php echo FULL_BASE_URL; ?>/img/download.png"></a>
    </span>
    <br>
    <sapn style="font-size: 30px;">AspectJ (.aj)
      <a id="aj_download" target="_blank"> > Download <img src="<?php echo FULL_BASE_URL; ?>/img/download.png"></a>
    </span>


  </div>

  <div class="col-md-6">


    <PRE class = "testscript" style="font-size: 10px;display: none;">
<?php 
echo $output_testcase;
?>
    </PRE>


    <PRE class = "ajscript" style="font-size: 10px;display: none;">
<?php 
echo $output_aspectj;
?>
    </PRE>
  </div>
</div>



