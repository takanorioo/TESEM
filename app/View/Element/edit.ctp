
<script>
	//属性の追加
	$(function() {
		var option = '<option value="">----</option>';

		<?php for($i = 0; $i < count($TYPE); $i++): ?>
		option  += '<option value=<?php echo $i;?>><?php echo h($TYPE[$i]);?></option>'
	<?php endfor; ?>

	var count = <?php echo $attribute_count;?>;
	$("#add_attribute").click(function(){
		$(".add_attribute").append('<tr><td><select name="data[Attribute][type]['+count+']" class="form-control">'+option+'</select></td><td><input name="data[Attribute][name]['+count+']" id="" class="form-control" placeholder="AttributeName" type="text"/></td></tr>');
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
		$(".add_method").append('<tr><td><select name="data[Method][type]['+count+']" class="form-control">'+option+'</select></td><td><input name="data[Method][name]['+count+']" id="" class="form-control" placeholder="MethodName" type="text"/></td></tr>');
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
		$(".add_relation").append('<tr><td><select name="data[Relation][label_relation_id]['+count+']" class="form-control">'+option_relation+'</select></td></tr>');
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



<script>
	function init(){

		dimension(1000, 1000);

		var uml = Joint.dia.uml;
		Joint.paper("world", 5000, 5000);


		<?php for($i = 0; $i < count($elements); $i++): ?>

		var <?php echo h($elements[$i]['Label']['name']);?> = uml.Class.create({
			rect: {x: <?php echo h($elements[$i]['Label']['position_x']);?>, y: <?php echo h($elements[$i]['Label']['position_y']);?>, width: <?php echo h($elements[$i]['width']);?>, height: <?php echo h($elements[$i]['height']);?> , hoge:1 },
			label: "<<<?php echo h($elements[$i]['Label']['interface']);?>>>\n<?php echo h($elements[$i]['Label']['name']);?>",
			swimlane1OffsetY: 30,
			shadow: true,
			attrs: {
				fill: "white"
			},
			labelAttrs: {
				'font-weight': '<?php echo h($elements[$i]['Label']['id']);?>',
			},
			<?php if(!empty($elements[$i]['Attribute'])): ?>
			attributes: [
			<?php for($j = 0; $j < count($elements[$i]['Attribute']); $j++): ?>
			"<?php echo $TYPE[$elements[$i]['Attribute'][$j]['type']];?> : <?php echo h($elements[$i]['Attribute'][$j]['name']);?>",
		<?php endfor; ?>
		],
	<?php endif; ?>

	<?php if(!empty($elements[$i]['Method'])): ?>
	methods: [
	<?php for($j = 0; $j < count($elements[$i]['Method']); $j++): ?>
	"<?php echo h($RETURNVALUE[$elements[$i]['Method'][$j]['type']]);?> : <?php echo h($elements[$i]['Method'][$j]['name']);?>",
<?php endfor; ?>
],
<?php endif; ?>

});

<?php endfor; ?>


var all = [
<?php for($i = 0; $i < count($elements); $i++): ?>
	<?php echo h($elements[$i]['Label']['name']);?>,
<?php endfor; ?>
];


<?php for($i = 0; $i < count($elements); $i++): ?>
	<?php for($j = 0; $j < count($elements[$i]['Relation']); $j++): ?>
	<?php echo h($elements[$i]['Label']['name']);?>.joint(<?php echo h($elements[$i]['Relation'][$j]['name']);?>, uml.arrow).register(all);
<?php endfor; ?>
<?php endfor; ?>


}

</script>




<div>
	<div>
		<div class="row">
			<div style="padding-top: 20px;">
				<span style="font-size: 40px;margin-left: 10px;">Element : <?php echo $elemet['Label']['name'];?></span>
				
				<a  href="/<?php echo $base_dir;?>/element/delete/<?php echo $label_id;?>" onclick="return confirm('Are You Sure ?');" style="margin: 10px;">Delete: <img src="/<?php echo $base_dir;?>/img/delete_icon.png" style="margin-top: -7px;"></a>
			</div>
		</div>

		<?php echo $this->Form->create('Label', array('id' => false)); ?>

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
								<td><?php echo $this->Form->input('Label.interface', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => ' form-control', 'placeholder' => 'StereoType', 'error'=>false)); ?></td>
								<td><?php echo $this->Form->input('Label.name', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Element Name', 'error'=>false)); ?></td>
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
							<?php if(!empty($elemet['Attribute']['type'])): ?>
								<?php for($i = 0; $i < count($elemet['Attribute']['type']); $i++): ?>
									<tr>
										<?php echo $this->Form->hidden('Attribute.id.'.$i, array('label' => false, 'div' => false, 'id' => false)); ?>
										<td><?php echo $this->Form->input('Attribute.type.'.$i, array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $TYPE, 'class' => 'form-control', 'error'=>false)); ?>
										</td>
										<td><?php echo $this->Form->input('Attribute.name.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'AttributeName', 'error'=>false)); ?>
										</td>
										<td><a href="/<?php echo $base_dir;?>/element/attribute_delete/<?php echo $elemet['Attribute']['id'][$i];?>" onclick="return confirm('Are You Sure ?');" style="margin: 10px;font-size: 20px;color: red;"><img src="/<?php echo $base_dir;?>/img/delete_icon.png" style="margin-top: 5px;"></a>
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
							<?php if(!empty($elemet['Method']['type'])): ?>
								<?php for($i = 0; $i < count($elemet['Method']['type']); $i++): ?>
									<tr>
										<?php echo $this->Form->hidden('Method.id.'.$i, array('label' => false, 'div' => false, 'id' => false)); ?>
										<td><?php echo $this->Form->input('Method.type.'.$i, array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $RETURNVALUE, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?>
										</td>
										<td><?php echo $this->Form->input('Method.name.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'AttributeName', 'error'=>false)); ?>
										</td>
										<td><a href="/<?php echo $base_dir;?>/element/method_delete/<?php echo $elemet['Method']['id'][$i];?>" onclick="return confirm('Are You Sure ?');" style="margin: 10px;font-size: 20px;color: red;"><img src="/<?php echo $base_dir;?>/img/delete_icon.png" style="margin-top: 5px;"></a>
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
							<?php if(!empty($elemet['Relation']['label_relation_id'])): ?>
								<?php for($i = 0; $i < count($elemet['Relation']['label_relation_id']); $i++): ?>
									<tr>
										<?php echo $this->Form->hidden('Relation.id.'.$i, array('label' => false, 'div' => false, 'id' => false)); ?>
										<td><?php echo $this->Form->input('Relation.label_relation_id.'.$i, array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $relation, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?>
										</td>
										<td><a href="/<?php echo $base_dir;?>/element/relation_delete/<?php echo $elemet['Relation']['id'][$i];?>" onclick="return confirm('Are You Sure ?');" style="margin: 10px;font-size: 20px;color: red;"><img src="/<?php echo $base_dir;?>/img/delete_icon.png" style="margin-top: 5px;"></a>
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

<div id="tabcontent">
	<div class="row"style = "padding-top:20px">
		<div id="world"></div>
	</div>
	
</div>
