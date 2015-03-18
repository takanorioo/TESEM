<script>
	//属性の追加
	$(function() {
		var option = '<option value="">----</option>';

		<?php for($i = 0; $i < count($TYPE); $i++): ?>
		option  += '<option value=<?php echo $i;?>><?php echo h($TYPE[$i]);?></option>'
	<?php endfor; ?>

	var count = <?php echo $attribute_count;?>;
	$("#add_attribute").click(function(){
		$(".add_attribute").append('<tr><td><select name="data[PatternAttribute][type]['+count+']" class="form-control">'+option+'</select></td><td><input name="data[PatternAttribute][name]['+count+']" id="" class="form-control" placeholder="AttributeName" type="text"/></td></tr>');
		count ++;
	});
});

	//操作の追加
	$(function() {
		var option = '<option value="">----</option>';
		<?php for($i = 0; $i < count($RETURNVALUE); $i++): ?>
		option  += '<option value=<?php echo $i;?>><?php echo h($RETURNVALUE[$i]);?></option>'
	<?php endfor; ?>

	var count = <?php echo $method_count;?>;
	$("#add_method").click(function(){
		$(".add_method").append('<tr><td><select name="data[PatternMethod][type]['+count+']" class="form-control">'+option+'</select></td><td><input name="data[PatternMethod][name]['+count+']" id="" class="form-control" placeholder="MethodName" type="text"/></td></tr>');
		count ++;
	});

});

	//リレーションの追加
	$(function() {
		var option_relation = '<option value="">----</option>';
		
		<?php for($i = 0; $i < count($option_relation); $i++): ?>
		option_relation  += '<option value=<?php echo h($option_relation[$i]['id']);?>><?php echo h($option_relation[$i]['name']);?></option>'
	<?php endfor; ?>

	var count = <?php echo $relation_count;?>;
	$("#add_relation").click(function(){
		$(".add_relation").append('<tr><td><select name="data[PatternRelation][pattern_element_relation_id]['+count+']" class="form-control">'+option_relation+'</select></td></tr>');
		count ++;
	});
});

</script>

<script type="text/javascript">

	function dimension(w, h) {
		var world = document.getElementById('world');
		world.style.width = w + 'px';
		world.style.height = h + 'px';
	}
</script>




<div>
	<div>
		<div class="row">
			<div style="padding-top: 20px;">
				<span style="font-size: 40px;margin-left: 10px;">Element : <?php echo $pattern_element['PatternElement']['element'];?></span>
				
				<a  href="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/element/delete/<?php echo $pattern_element_id;?>" onclick="return confirm('Are You Sure ?');" style="margin: 10px;">Delete: <img src="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/img/delete_icon.png" style="margin-top: -7px;"></a>
			</div>
		</div>

		<?php echo $this->Form->create('PatternElement', array('id' => false)); ?>

		<div>
			<div class="row" style = "padding-top:40px">
				<div class="col-md-8 well">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>StereoType</th>
								<th>Name</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $this->Form->input('PatternElement.interface', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => ' form-control', 'placeholder' => 'StereoType', 'error'=>false)); ?></td>
								<td><?php echo $this->Form->input('PatternElement.element', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Element Name', 'error'=>false)); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 well element">
					<p style="text-align: right;">
						<input id="add_attribute" type="button" class ="btn btn-success" value="+" />
					</p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Type</th>
								<th>AttributeName</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody class="add_attribute">
							<?php if(!empty($pattern_element['PatternAttribute']['type'])): ?>
								<?php for($i = 0; $i < count($pattern_element['PatternAttribute']['type']); $i++): ?>
									<tr>
										<?php echo $this->Form->hidden('PatternAttribute.id.'.$i, array('label' => false, 'div' => false, 'id' => false)); ?>
										<td><?php echo $this->Form->input('PatternAttribute.type.'.$i, array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $TYPE, 'class' => 'form-control', 'error'=>false)); ?>
										</td>
										<td><?php echo $this->Form->input('PatternAttribute.name.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'AttributeName', 'error'=>false)); ?>
										</td>
										<td><a href="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/patterns/attribute_delete/<?php echo $pattern_element['PatternAttribute']['id'][$i];?>" onclick="return confirm('Are You Sure ?');" style="margin: 10px;font-size: 20px;color: red;"><img src="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/img/delete_icon.png" style="margin-top: 5px;"></a>
										</td>
									</tr>
								<?php endfor; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
				<div class="col-md-4 well element">
					<p style="text-align: right;">
						<input id="add_method" type="button" class ="btn btn-success" value="+" />
					</p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Return</th>
								<th>MethodName</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody class="add_method">
							<?php if(!empty($pattern_element['PatternMethod']['type'])): ?>
								<?php for($i = 0; $i < count($pattern_element['PatternMethod']['type']); $i++): ?>
									<tr>
										<?php echo $this->Form->hidden('PatternMethod.id.'.$i, array('label' => false, 'div' => false, 'id' => false)); ?>
										<td><?php echo $this->Form->input('PatternMethod.type.'.$i, array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $RETURNVALUE, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?>
										</td>
										<td><?php echo $this->Form->input('PatternMethod.name.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'AttributeName', 'error'=>false)); ?>
										</td>
										<td><a href="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/patterns/method_delete/<?php echo $pattern_element['PatternMethod']['id'][$i];?>" onclick="return confirm('Are You Sure ?');" style="margin: 10px;font-size: 20px;color: red;"><img src="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/img/delete_icon.png" style="margin-top: 5px;"></a>
										</td>
									</tr>

								<?php endfor; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
				<div class="col-md-4 well element">
					<p style="text-align: right;">
						<input id="add_relation" type="button" class ="btn btn-success" value="+" />
					</p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Relation</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody class="add_relation">
							<?php if(!empty($pattern_element['PatternRelation']['pattern_element_relation_id'])): ?>
								<?php for($i = 0; $i < count($pattern_element['PatternRelation']['pattern_element_relation_id']); $i++): ?>
									<tr>
										<?php echo $this->Form->hidden('PatternRelation.id.'.$i, array('label' => false, 'div' => false, 'id' => false)); ?>
										<td><?php echo $this->Form->input('PatternRelation.pattern_element_relation_id.'.$i, array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $relation, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?>
										</td>
										<td><a href="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/patterns/relation_delete/<?php echo $pattern_element['PatternRelation']['id'][$i];?>" onclick="return confirm('Are You Sure ?');" style="margin: 10px;font-size: 20px;color: red;"><img src="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/img/delete_icon.png" style="margin-top: 5px;"></a>
										</td>
									</tr>
								</tbody>
							<?php endfor; ?>
						<?php endif; ?>
					</table>
				</div>
				<div class="col-md-12">
					<p style="text-align: center;">
						<?php
						echo $this->Form->submit('Edit Element', array('name' => 'confirm', 'div' => false, 'class' => 'btn btn-primary'));
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


