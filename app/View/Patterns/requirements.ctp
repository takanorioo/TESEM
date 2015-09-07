<div style="float: right;">
</div>
<h3>▶ Security Design Requirements </h3>

<?php if (!empty($pattern_conditions)): ?>

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

						<td><?php echo $pattern_conditions[$i]["PatternCondition"]["condition"] ?><a href="<?php echo FULL_BASE_URL; ?>/patterns/delete_condition/<?php echo h($pattern_conditions[$i]['PatternCondition']['id']);?>"><img src="<?php echo FULL_BASE_URL; ?>/img/delete_icon.png" style="width: auto; max-height: 15px;float: right;"></a></td>
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
			<?php for($i = 0; $i < count($pattern_actions); $i++): ?>
				<tr>
					<?php if ($i == 0): ?>
						<td rowspan= "<?php echo pow(2,count($pattern_actions));?>">Actions</td>
					<?php endif; ?>

						<td><?php echo $pattern_actions[$i]["PatternAction"]["action"] ?><a href="<?php echo FULL_BASE_URL; ?>/patterns/delete_action/<?php echo h($pattern_actions[$i]['PatternAction']['id']);?>"><img src="<?php echo FULL_BASE_URL; ?>/img/delete_icon.png" style="width: auto; max-height: 15px;float: right;"></a></td>

					<?php for($t = 1; $t <=  pow (2, count($pattern_conditions)); $t++): ?>
						<?php if(!empty($checked[$i][$t])): ?>
							<th>×</th>
						<?php else: ?>
							<th></th>
						<?php endif; ?>
					<?php endfor; ?>
				</tr>
			<?php endfor; ?>
<!--
				<tr>
						<td>Execute</td>
							<th>×</th>
						<?php for($j = 0; $j < pow(2, count($pattern_requirements))-1; $j++): ?>
							<th></th>
						<?php endfor; ?>
				</tr>
				<tr>
						<td>Not Execute</td>
							<th></th>
						<?php for($j = 0; $j < pow(2, count($pattern_requirements))-1; $j++): ?>
							<th>×</th>
						<?php endfor; ?>


				</tr>
-->
		</tbody>
	</table>
<?php endif; ?>
<h1>
<a href="<?php echo FULL_BASE_URL; ?>/patterns/add_condition/<?php echo h($pattern_id);?>" class ="btn btn-primary">Add Condition</a>
<?php if (!empty($pattern_conditions)): ?>
<a href="<?php echo FULL_BASE_URL; ?>/patterns/add_action/<?php echo h($pattern_id);?>" class ="btn btn-primary">Add Action</a>
<?php endif; ?>
</h1>

