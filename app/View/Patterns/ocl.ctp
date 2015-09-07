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
		
		<?php for($i = 0; $i < count($pattern_elements); $i++): ?>
		option_relation  += '<option value=<?php echo h($pattern_elements[$i]['PatternElement']['id']);?>><?php echo h($pattern_elements[$i]['PatternElement']['element']);?></option>'
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
				<span style="font-size: 40px;margin-left: 10px;">OCL</span>
				
<!--				<a  href="<?php echo FULL_BASE_URL; ?>/element/delete/<?php echo $pattern_element_id;?>" onclick="return confirm('Are You Sure ?');" style="margin: 10px;">Delete: <img src="<?php echo FULL_BASE_URL; ?>/img/delete_icon.png" style="margin-top: -7px;"></a>
-->
			</div>
		</div>

		<?php echo $this->Form->create('PatternElement', array('id' => false)); ?>

		<div>
			<div class="row" style = "padding-top:40px">
				<div class="col-md-8 well">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>OCL</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $this->Form->input('PatternOcl.ocl', array('label' => false, 'div' => false, 'id' => '', 'type' => 'textarea', 'class' => ' form-control', 'placeholder' => 'OCL', 'error'=>false, 'value'=>$pattern_ocl['PatternOcl']["ocl"])); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
				<div class="col-md-12">
					<p style="text-align: center;">
						<?php
						echo $this->Form->submit('Edit OCL', array('name' => 'confirm', 'div' => false, 'class' => 'btn btn-primary'));
						?>
					</p>
					<input type="hidden" name="token" value="<?php echo session_id();?>">
					<input type="hidden" name="editOCL" value="editOCL">
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>


