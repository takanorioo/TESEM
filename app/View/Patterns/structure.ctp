

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

<h3 style="padding-bottom: 20px;">Security Design Pattern </h3>

<ul class="nav nav-tabs" role="tablist" id="myTab">
  <li><a href="<?php echo FULL_BASE_URL; ?>/patterns/structure/<?php echo $pattern_id; ?>">Structure</a></li>
  <li><a href="<?php echo FULL_BASE_URL; ?>/patterns/behavior/<?php echo $pattern_id; ?>">Behabior</a></li>
  <li><a href="<?php echo FULL_BASE_URL; ?>/patterns/elements/<?php echo $pattern_id; ?>">Elements</a></li>
  <li><a href="<?php echo FULL_BASE_URL; ?>/patterns/requiremetns/<?php echo $pattern_id; ?>">Pattern Requiremetns</a></li>
  <li><a href="<?php echo FULL_BASE_URL; ?>/patterns/ocl/<?php echo $pattern_id; ?>">OCL</a></li>
</ul>

<div class="tab-content">

  <!--Structure  -->
  <div  style="padding-top: 30px;">
    <div class="row" style="padding-top: 40px;position: absolute;right: 50px;">
      <input id="setPattern" type="button" name ="setPattern" class ="btn btn-primary" value="Set Layout" style="font-size: 20px;">
    </div>
    <div id="world"></div>
  </div>

</div>


