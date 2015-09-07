<div style="float: right;">
</div>
	<?php echo $this->Form->create('PatternAction'); ?>
	<table class="table table-bordered">
		<thead>
			<tr style="background: lightgray;">
				<td colspan="2"></td>
				<?php for($i = 0; $i < pow(2,count($pattern_conditions)); $i++): ?>
					<th><?php echo $i+1;?></th>
				<?php endfor; ?>
			</tr>
		</thead>
		<tbody>
			<?php for($i = 0; $i<count($pattern_conditions); $i++): ?>
				<tr>
					<?php if ($i == 0): ?>
						<td rowspan="<?php echo count($pattern_conditions);?>">Conditions</td>
					<?php endif; ?>

						<td><?php echo $pattern_conditions[$i]["PatternCondition"]["condition"] ?></td>
					<?php for($t = 0; $t <  pow (2, $i); $t++): ?>
						<?php for($j = 0; $j < pow(2, count($pattern_conditions))/pow(2,$i+1); $j++): ?>
							<th>Yes</th>
						<?php endfor; ?>

						<?php for($j = 0; $j < pow(2, count($pattern_conditions))/pow(2,$i+1); $j++): ?>
							<th>No</th>
						<?php endfor; ?>
					<?php endfor; ?>
				</tr>
			<?php endfor; ?>
				<tr>
						<td rowspan= "<?php echo count($pattern_actions)+1;?>">Actions</td>
			<?php for($i = 0; $i < count($pattern_actions); $i++): ?>
				<?php if($i) echo "<tr>"; ?>
						<td><?php echo $pattern_actions[$i]["PatternAction"]["action"] ?></td>

					<?php for($t = 1; $t <=  pow (2, count($pattern_conditions)); $t++): ?>
						<?php if(!empty($checked[$i][$t])): ?>
							<th>×</th>
						<?php else: ?>
							<th></th>
						<?php endif; ?>
					<?php endfor; ?>
				</tr>
			<?php endfor; ?>

						<td><?php echo $this->Form->input('PatternAction.action', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Action', 'error'=>false)); ?></td>

						<?php for($j = 1; $j <= pow(2, count($pattern_conditions)); $j++): ?>
							<th><?php echo $this->Form->input('PatternAction.checked.'.$j, array('label' => false, 'div' => false, 'id' => '', 'type' => 'checkbox', 'class' => '', 'placeholder' => '', 'error'=>false)); ?></th>
						<?php endfor; ?>
				</tr>

		</tbody>
	</table>
	<div class="row">
		<div class="col-md-12">
			<p style="text-align: center;">
			<?php
			echo $this->Form->submit('Add Action', array('name' => 'confirm', 'div' => false, 'class' => 'btn btn-primary'));
			?>
			</p>
		<input type="hidden" name="token" value="<?php echo session_id();?>">
		<input type="hidden" name="addAction" value="addAction">
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
