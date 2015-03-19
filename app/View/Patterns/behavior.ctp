
<?php 
echo $this->Html->css(array('joint.behaviors'));
echo $this->Html->script(array('joint.shapes.erd'));

?>



<script>

  //属性の追加
  $(function() {
  	var type_option = '<option value="">----</option>';
  	var element_option = '<option value="">----</option>';
  	var count = 0;

  	<?php for($i = 1; $i <= count($BEHAVIOR); $i++): ?>
  	type_option  += '<option value=<?php echo $i;?>><?php echo h($BEHAVIOR[$i]);?></option>'
  <?php endfor; ?>
  
  <?php for($i = 0; $i < count($option_relation); $i++): ?>
  element_option  += '<option value=<?php echo h($option_relation[$i]['id']);?>><?php echo h($option_relation[$i]['name']);?></option>'
<?php endfor; ?>

<?php if(!empty($behavior_count)): ?>
	var count = <?php echo $behavior_count;?>;
<?php endif; ?>

$("#add_behavior").click(function(){
	$(".add_behavior").append('<tr><td><select name="data[PatternBehavior][type]['+count+']" class="form-control">'+type_option+'</select></td><td><select name="data[PatternBehavior][pattern_element_id]['+count+']" id="" class="form-control" placeholder="AttributeName">'+element_option+'</select></td></tr>');
	count ++;
});
});

  //属性の追加
  $(function() {
  	var element_from_option = '<option value="">----</option>';
  	var element_to_option = '<option value="">----</option>';
  	var action_count = 0;

  	<?php if(!empty($option_behabior_relation)): ?>
  	<?php for($i = 0; $i < count($option_behabior_relation); $i++): ?>
  	element_from_option  += '<option value=<?php echo h($option_behabior_relation[$i]['id']);?>><?php echo h($option_behabior_relation[$i]['name']);?></option>'
  <?php endfor; ?>

  <?php for($i = 0; $i < count($option_behabior_relation); $i++): ?>
  element_to_option  += '<option value=<?php echo h($option_behabior_relation[$i]['id']);?>><?php echo h($option_behabior_relation[$i]['name']);?></option>'
<?php endfor; ?>
<?php endif; ?>

<?php if(!empty($behavior_action_count)): ?>
	var action_count = <?php echo $behavior_action_count;?>;
<?php endif; ?>

$("#add_action").click(function(){
	$(".add_action").append('<tr><td><select name="data[PatternBehaviorRelations][pattern_behavior_id]['+action_count+']" id="" class="form-control" placeholder="AttributeName">'+element_from_option+'</select></td><td><select name="data[PatternBehaviorRelations][behavior_relation_id]['+action_count+']" id="" class="form-control" placeholder="AttributeName">'+element_to_option+'</select></td><td><input name="data[PatternBehaviorRelations][guard]['+action_count+']" id="" class="form-control" placeholder="[Guard]" type="text"/></td><td><input name="data[PatternBehaviorRelations][action]['+action_count+']" id="" class="form-control" placeholder="Action" type="text"/></td><td><input name="data[PatternBehaviorRelations][order]['+action_count+']" id="" class="form-control" placeholder="order" type="text"/></td></tr>');
	action_count ++;
});
});

</script>


<div style="float: right;">
	<img src="<?php echo FULL_BASE_URL; ?>/img//kobashi.jpg" style="width: 80px;margin-right: 10px;" class="img-thumbnail">
	<div style="float: right;margin-top: 10px;width: 180px;">
		<span>Contributer :</span><br>
		<span>Takanori Kobashi</span><br>
		<span>Created : 2014/5/12</span>
	</div>
</div>


<h3 style="padding-bottom: 20px;">Selected Countermeasure </h3>

<ul class="nav nav-tabs" role="tablist" id="myTab">
  <li><a href="<?php echo FULL_BASE_URL; ?>/patterns/structure/<?php echo $pattern_id; ?>">Structure</a></li>
  <li><a href="<?php echo FULL_BASE_URL; ?>/patterns/behavior/<?php echo $pattern_id; ?>">Behabior</a></li>
  <li><a href="<?php echo FULL_BASE_URL; ?>/patterns/elements/<?php echo $pattern_id; ?>">Elements</a></li>
  <li><a href="<?php echo FULL_BASE_URL; ?>/patterns/requiremetns/<?php echo $pattern_id; ?>">Pattern Requiremetns</a></li>
  <li><a href="<?php echo FULL_BASE_URL; ?>/patterns/ocl/<?php echo $pattern_id; ?>">OCL</a></li>
</ul>

<div class="tab-content">

	


	<!-- Behavior -->
	<div id="profile" style="padding-top: 30px;">

		<div class="row" style="padding-top: 40px;position: absolute;right: 100px;">
			<input id="setPatternBehavior" type="button" name ="setPatternBehavior" class ="btn btn-primary" value="Set Layout" style="font-size: 20px;">
		</div>
		<section id="demos" style="margin-bottom: 20px;">
			<div id="fsa" class="content-container">
				<div id="paper" class="paper"/>
			</div>
		</section>



		<script type="text/javascript">

			var graph = new joint.dia.Graph;

			var paper = new joint.dia.Paper({
				el: $('#paper'),
				width: 2800,
				height: 2600,
				gridSize: 1,
				model: graph
			});

			function state(x, y, label, id) {

				var cell = new joint.shapes.fsa.State({
					position: { x: x, y: y },
					size: { width: 200, height: 50 },
					attrs: { text : { text: label || '', 'font-weight': id}},

				});
				graph.addCell(cell);
				return cell;
			};

			function link(source, target, label, vertices, id) {

				var cell = new joint.shapes.fsa.Arrow({
					source: { id: source.id },
					target: { id: target.id },
					labels: [{ position: .5, attrs: { text: { text: label || '', 'font-weight': id } } }],
					vertices: vertices || []
				});
				graph.addCell(cell);
				return cell;
			}

			<?php for($i = 0; $i < count($behaviors); $i++): ?>
			var <?php echo h($behaviors[$i]['PatternElement']['element']);?>  = state(<?php echo $behaviors[$i]['PatternBehavior']['position_x'];?>, <?php echo $behaviors[$i]['PatternBehavior']['position_y'];?>, '<<<?php echo $BEHAVIOR[$behaviors[$i]['PatternBehavior']['type']];?>>>\n<?php echo h($behaviors[$i]['PatternElement']['element']);?>', <?php echo h($behaviors[$i]['PatternBehavior']['id']);?>);
		<?php endfor; ?>


		<?php for($i = 0; $i < count($behaviors); $i++): ?>
		<?php for($j = 0; $j < count($behaviors[$i]['PatternBehaviorRelations']); $j++): ?>
		link(<?php echo h($behaviors[$i]['PatternBehaviorRelations'][$j]['pattern_behavior_name']);?>, <?php echo h($behaviors[$i]['PatternBehaviorRelations'][$j]['pattern_behavior_relation_name']);?>,  '<?php echo h($behaviors[$i]['PatternBehaviorRelations'][$j]['order']);?>: <?php echo h($behaviors[$i]['PatternBehaviorRelations'][$j]['guard']);?> <?php echo h($behaviors[$i]['PatternBehaviorRelations'][$j]['action']);?>', [{x: <?php echo h($behaviors[$i]['PatternBehaviorRelations'][$j]['position_x']);?>, y: <?php echo h($behaviors[$i]['PatternBehaviorRelations'][$j]['position_y']);?>}], <?php echo h($behaviors[$i]['PatternBehaviorRelations'][$j]['id']);?>);
	<?php endfor; ?>
<?php endfor; ?>


</script>

<ul class="nav nav-tabs" role="tablist" id="myTab">
  <li><a href="#tab1" role="tab" data-toggle="tab">Elements</a></li>
  <li><a href="#tab2" role="tab" data-toggle="tab">Action</a></li>
</ul>

<div class="tab-content">

  <!--Structure  -->
  <div class="tab-pane  active" id="tab1"  style="padding-top: 30px;">
    <div>
      <div>
        <?php echo $this->Form->create('Label', array('id' => false)); ?>
        <div>
          <div class="row">
            <div class="col-md-12 well element">
              <p style="text-align: right;">
                <input id="add_behavior" type="button" class ="btn btn-success" value="+" />
              </p>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Element</th>
                    <th>Delete</th>
                  </tr>
                </thead>
                <tbody class="add_behavior">
                  <?php if(!empty($behaviors_data['PatternBehavior']['id'])): ?>
                    <?php for($i = 0; $i < count($behaviors_data['PatternBehavior']['id']); $i++): ?>
                      <tr>
                        <?php echo $this->Form->hidden('PatternBehavior.id.'.$i,  array('label' => false, 'div' => false, 'id' => false)); ?>
                        <td><?php echo $this->Form->input('PatternBehavior.type.'.$i, array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $BEHAVIOR, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?>
                        </td>
                        <td><?php echo $this->Form->input('PatternBehavior.pattern_element_id.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'select', 'class' => 'form-control','options' => $relation,  'placeholder' => 'AttributeName', 'error'=>false)); ?>
                        </td>
                        <td><a href="<?php echo FULL_BASE_URL; ?>/pattern/behabior_element_delete/<?php echo $behaviors_data['PatternBehavior']['id'][$i];?>" onclick="return confirm('Are You Sure ?');" style="margin: 10px;font-size: 20px;color: red;"><img src="<?php echo FULL_BASE_URL; ?>/img/delete_icon.png" style="margin-top: 5px;"></a>
                        </td>
                      </tr>
                    <?php endfor; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

            <div class="col-md-12">
              <p style="text-align: center;">
                <?php echo $this->Form->submit('Edit Element', array('name' => 'editElement', 'div' => false, 'class' => 'btn btn-primary'));
                ?>
              </p>
              <input type="hidden" name="token" value="<?php echo session_id();?>">
              <input type="hidden" name="editElement" value="editElement">
            </div>
            <?php echo $this->Form->end(); ?>
          </div>
        </div>
        
      </div>
    </div>
  </div>


  <div class="tab-pane fade" id="tab2" style="padding-top: 30px;">
    <div>
      <div>
        <?php echo $this->Form->create('Label', array('id' => false)); ?>
        <div>
          <div class="row">
            <div class="col-md-12 well element">
              <p style="text-align: right;">
                <input id="add_action" type="button" class ="btn btn-success" value="+" />
              </p>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Element (From)</th>
                    <th>Element (To)</th>
                    <th>Guard</th>
                    <th>Action</th>
                    <th>Order</th>
                    <th>Delete</th>
                  </tr>
                </thead>
                <tbody class="add_action">
                  <?php if(!empty($behaviors_data['PatternBehaviorRelations']['id'])): ?>
                    <?php for($i = 0; $i < count($behaviors_data['PatternBehaviorRelations']['id']); $i++): ?>
                      <tr>
                        <?php echo $this->Form->hidden('PatternBehaviorRelations.id.'.$i,  array('label' => false, 'div' => false, 'id' => false)); ?>
                        <td><?php echo $this->Form->input('PatternBehaviorRelations.pattern_behavior_id.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'select', 'class' => 'form-control','options' => $behabior_relation,  'placeholder' => 'AttributeName', 'error'=>false)); ?>
                        </td>
                        <td><?php echo $this->Form->input('PatternBehaviorRelations.behavior_relation_id.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'select', 'class' => 'form-control','options' => $behabior_relation,  'placeholder' => 'AttributeName', 'error'=>false)); ?>
                        </td>
                        <td><?php echo $this->Form->input('PatternBehaviorRelations.guard.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control',  'placeholder' => '[Guard]', 'error'=>false)); ?>
                        </td>
                        <td><?php echo $this->Form->input('PatternBehaviorRelations.action.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control',  'placeholder' => 'Action', 'error'=>false)); ?>
                        </td>
                        <td><?php echo $this->Form->input('PatternBehaviorRelations.order.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control',  'placeholder' => 'Order', 'error'=>false)); ?>
                        </td>
                        <td><a href="<?php echo FULL_BASE_URL; ?>/behavior/action_delete/<?php echo $behaviors_data['PatternBehaviorRelations']['id'][$i];?>" onclick="return confirm('Are You Sure ?');" style="margin: 10px;font-size: 20px;color: red;"><img src="<?php echo FULL_BASE_URL; ?>/img/delete_icon.png" style="margin-top: 5px;"></a>
                        </td>
                      </tr>
                    <?php endfor; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

            <div class="col-md-12">
              <p style="text-align: center;">
                <?php
                echo $this->Form->submit('Edit Action', array('name' => 'editAction', 'div' => false, 'class' => 'btn btn-primary'));
                ?>
              </p>
              <input type="hidden" name="token" value="<?php echo session_id();?>">
              <input type="hidden" name="editAction" value="editAction">
            </div>
            <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>


