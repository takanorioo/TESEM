
<script>
	//属性の追加
	$(function() {
		var type_option = '<option value="">----</option>';
		var element_option = '<option value="">----</option>';

	<?php for($i = 1; $i <= count($BEHAVIOR); $i++): ?>
		type_option  += '<option value=<?php echo $i;?>><?php echo h($BEHAVIOR[$i]);?></option>'
	<?php endfor; ?>
	
	<?php for($i = 0; $i < count($option_relation); $i++): ?>
		element_option  += '<option value=<?php echo h($option_relation[$i]['id']);?>><?php echo h($option_relation[$i]['name']);?></option>'
	<?php endfor; ?>

	var count = <?php echo $behavior_count;?>;

	$("#add_behavior").click(function(){
		$(".add_behavior").append('<tr><td><select name="data[Behavior][type]['+count+']" class="form-control">'+type_option+'</select></td><td><select name="data[Behavior][label_id]['+count+']" id="" class="form-control" placeholder="AttributeName">'+element_option+'</select></td></tr>');
		count ++;
	});
});

</script>



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
							</tr>
						</thead>
						<tbody class="add_behavior">
							<?php if(!empty($behaviors['Behavior']['id'])): ?>
								<?php for($i = 0; $i < count($behaviors['Behavior']['id']); $i++): ?>
									<tr>
										<?php echo $this->Form->hidden('Behavior.id.'.$i,  array('label' => false, 'div' => false, 'id' => false)); ?>
										<td><?php echo $this->Form->input('Behavior.type.'.$i, array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $BEHAVIOR, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?>
										</td>
										<td><?php echo $this->Form->input('Behavior.label_id.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'select', 'class' => 'form-control','options' => $relation,  'placeholder' => 'AttributeName', 'error'=>false)); ?>
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


