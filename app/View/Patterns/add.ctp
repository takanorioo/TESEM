<script>

	//属性の追加
	$(function() {
		var option = '<option value="">----</option>';
		<?php for($i = 0; $i < count($TYPE); $i++): ?>
		option  += '<option value=<?php echo $i;?>><?php echo h($TYPE[$i]);?></option>'
	<?php endfor; ?>

	var count = 1;
	$("#add_attribute").click(function(){
		$(".add_attribute").append('<tr><td><select name="data[PatternAttribute][type]['+count+']" class="form-control">'+option+'</select></td><td><input name="data[PatternAttribute][name]['+count+']" id="" class="form-control" placeholder="AttributeName" type="text"/></td></tr>');
		count ++;
	});
});

	//操作の追加
	$(function() {
		var option = '<option value="">----</option>';
		<?php for($i = 0; $i < count($RETURNVALUE); $i++): ?>
		option  += '<option value=<?php echo h($RETURNVALUE[$i]);?>><?php echo h($RETURNVALUE[$i]);?></option>'
	<?php endfor; ?>

	var count = 1;
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

	var count = 1;
	$("#add_relation").click(function(){
		$(".add_relation").append('<tr><td><select name="data[PatternRelation][id]['+count+']"class="form-control">'+option_relation+'</select></td></tr>');
		count ++;
	});
});

</script>

<div>
	<div>
		<div class="row">
			<div style="padding-top: 20px;">
				<p style="font-size: 40px;margin-left: 10px;">Add Security Pattern</p>
			</div>
		</div>
		<?php echo $this->Form->create('Pattern'); ?>
		<div class="row">
			<div class="col-md-8 well">
				<table class="table table-hover">
                                        <thead>
                                                <tr>
                                                        <th>Pattern Name</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                <tr>
                                                        <td><?php echo $this->Form->input('Pattern.name', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Pattern Name', 'error'=>false)); ?></td>
                                                </tr>
                                        </tbody>
                                </table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<p style="text-align: center;">
					<?php
					echo $this->Form->submit('Add Pattern', array('name' => 'confirm', 'div' => false, 'class' => 'btn btn-primary'));
					?>
				</p>
				<input type="hidden" name="token" value="<?php echo session_id();?>">
				<input type="hidden" name="addPattern" value="addPattern">
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
