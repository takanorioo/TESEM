
<script>
</script>

<div>
	<div>
		<div class="row">
			<div style="padding-top: 20px;">
				<p style="font-size: 40px;margin-left: 10px;">Add Requirement</p>
			</div>
		</div>
		<?php echo $this->Form->create('PatternElement'); ?>
		<div class="row">
			<div class="col-md-8 well">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Condition</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $this->Form->input('PatternCondition.condition', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Condition', 'error'=>false)); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
<!--
		<div class="row">
			<div class="col-md-8 well">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>True Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $this->Form->input('PatternRequirementAction.action.0', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'True Action', 'error'=>false)); ?></td>
						</tr>
					</tbody>
				</table>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>False Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $this->Form->input('PatternRequirementAction.action.1', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'False Action', 'error'=>false)); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
-->
		<div class="row">
			<div class="col-md-12">
				<p style="text-align: center;">
					<?php
					echo $this->Form->submit('Add Condition', array('name' => 'confirm', 'div' => false, 'class' => 'btn btn-primary'));
					?>
				</p>
				<input type="hidden" name="token" value="<?php echo session_id();?>">
				<input type="hidden" name="addCondition" value="addCondition">
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
