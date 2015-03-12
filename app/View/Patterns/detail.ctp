<script>
  $(function () {
    $('#myTab a:first').tab('show')
  })
</script>


<script type="text/javascript">

  function dimension(w, h) {
    var world = document.getElementById('world');
    world.style.width = w + 'px';
    world.style.height = h + 'px';
  }
</script>



<script>

  function init(){

    dimension(1000, 1000);

    var uml = Joint.dia.uml;
    Joint.paper("world", 5000, 5000);


    <?php for($i = 0; $i < count($pattern_elements); $i++): ?>

    var <?php echo h($pattern_elements[$i]['PatternElement']['element']);?> = uml.Class.create({
      rect: {x: <?php echo h($pattern_elements[$i]['PatternElement']['position_x']);?>, y: <?php echo h($pattern_elements[$i]['PatternElement']['position_y']);?>, width: <?php echo h($pattern_elements[$i]['width']);?>, height: <?php echo h($pattern_elements[$i]['height']);?> , hoge:1 },
      label: "<<<?php echo h($pattern_elements[$i]['PatternElement']['interface']);?>>>\n<?php echo h($pattern_elements[$i]['PatternElement']['element']);?>",
      swimlane1OffsetY: 30,
      shadow: true,
      attrs: {
        fill: "white"
      },
      labelAttrs: {
        'font-weight': '<?php echo h($pattern_elements[$i]['PatternElement']['id']);?>',
      },
      <?php if(!empty($pattern_elements[$i]['PatternAttribute'])): ?>
      attributes: [
      <?php for($j = 0; $j < count($pattern_elements[$i]['PatternAttribute']); $j++): ?>
      "<?php echo $TYPE[$pattern_elements[$i]['PatternAttribute'][$j]['type']];?> : <?php echo h($pattern_elements[$i]['PatternAttribute'][$j]['name']);?>",
    <?php endfor; ?>
    ],
  <?php endif; ?>

  <?php if(!empty($pattern_elements[$i]['PatternMethod'])): ?>
  methods: [
  <?php for($j = 0; $j < count($pattern_elements[$i]['PatternMethod']); $j++): ?>
  "<?php echo h($RETURNVALUE[$pattern_elements[$i]['PatternMethod'][$j]['type']]);?> : <?php echo h($pattern_elements[$i]['PatternMethod'][$j]['name']);?>",
<?php endfor; ?>
],
<?php endif; ?>

});

<?php endfor; ?>


var all = [
<?php for($i = 0; $i < count($pattern_elements); $i++): ?>
  <?php echo h($pattern_elements[$i]['PatternElement']['element']);?>,
<?php endfor; ?>
];


<?php for($i = 0; $i < count($pattern_elements); $i++): ?>
  <?php for($j = 0; $j < count($pattern_elements[$i]['PatternRelation']); $j++): ?>
  <?php echo h($pattern_elements[$i]['PatternElement']['element']);?>.joint(<?php echo h($pattern_elements[$i]['PatternRelation'][$j]['name']);?>, uml.arrow).register(all);
<?php endfor; ?>
<?php endfor; ?>


}

</script>



<div style="float: right;">
  <img src="/<?php echo $base_dir;?>/img//kobashi.jpg" style="width: 80px;margin-right: 10px;" class="img-thumbnail">
  <div style="float: right;margin-top: 10px;width: 180px;">
  <span>Contributer :</span><br>
  <span>Takanori Kobashi</span><br>
  <span>Created : 2014/5/12</span>
  </div>
</div>


<h3 style="padding-bottom: 20px;">Selected Countermeasure </h3>

<ul class="nav nav-tabs" role="tablist" id="myTab">
  <li><a href="#home" role="tab" data-toggle="tab">Structure</a></li>
  <li><a href="#profile" role="tab" data-toggle="tab">Behabior</a></li>
  <li><a href="#messages" role="tab" data-toggle="tab">Elements</a></li>
  <li><a href="#messages" role="tab"  data-toggle="tab">Pattern Requiremetns</a></li>
  <li><a href="#messages" role="tab" data-toggle="tab">OCL</a></li>
</ul>

<div class="tab-content">

  <!--Structure  -->
  <div class="tab-pane fade active" id="home"  style="padding-top: 30px;">
    <div class="row" style="padding-top: 40px;position: absolute;right: 50px;">
      <input id="setPattern" type="button" name ="setPattern" class ="btn btn-primary" value="Set Layout" style="font-size: 20px;">
    </div>
    <div id="world"></div>
  </div>



  <div class="tab-pane fade" id="profile">g..eg.</div>

  <!--Elements  -->
  <div class="tab-pane fade" id="messages" style="padding-top: 20px;">

    <div style="float: right;">
      <h1 style="padding-left: 280px;">
        <a href="/<?php echo $base_dir;?>/patterns/add" class ="btn btn-primary">Add Pattern Elements</a>
      </h1>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr style="background: lightgray;">
          <th>#</th>
          <td>Elements</td>
          <td style="width: 310px;">Delete from Selected Countermeasure</td>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 0; $i < count($pattern['PatternElement']); $i++): ?>
          <tr>
            <td><?php echo $i + 1;?></td>
            <td><a href="/<?php echo $base_dir;?>/patterns/edit/<?php echo h($pattern['PatternElement'][$i]['id']);?>"><?php echo h($pattern['PatternElement'][$i]['element']);?></a></td>
            <td style="text-align: center;"><a href="/<?php echo $base_dir;?>/patterns/delete/<?php echo h($pattern['PatternElement'][$i]['id']);?>"><img src="/<?php echo $base_dir;?>/img/delete_icon.png" style="margin-top: 5px;"></a></td>
          </tr>
        <?php endfor; ?>
      </tboy>
    </table>
  </div>


</div>


