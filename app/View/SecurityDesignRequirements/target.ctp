<div style="float: right;">
	<h1 style="padding-left: 140px;"><a href="/<?php echo $base_dir;?>/security_design_requirements/table/<?php echo h($method['Method']['id']);?>" class ="btn btn-primary">Show Security Design Requirements</a></h1>
	<h1 style="margin-top: -44px;"><a href="/<?php echo $base_dir;?>/security_design_requirements/bind/<?php echo h($method['Method']['id']);?>" class ="btn btn-primary">Bind Elements</a></h1>
</div>

<h3 style="padding-top: 50px;">Set Security Design patterns</h3>
<?php echo $this->Form->create('Label'); ?>
<div class="row">
	<div class="col-md-4 well element">
		<p style="text-align: right;">
			<input id="add_countermeasure" type="button" class ="btn btn-success" value="+" />
		</p>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Pattern</th>
				</tr>
			</thead>
			<tbody class="add_countermeasure">
				<tr>
					<td><?php echo $this->Form->input('Pattern.0', array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $pattern_list, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="col-md-12">
	<p>
		<?php
		echo $this->Form->submit('Set Security Design Patterns', array('name' => 'selectPattern', 'div' => false, 'class' => 'btn btn-primary'));
		?>
	</p>
	<input type="hidden" name="token" value="<?php echo session_id();?>">
	<input type="hidden" name="selectPattern" value="selectPattern">
</div>
<?php echo $this->Form->end(); ?>

