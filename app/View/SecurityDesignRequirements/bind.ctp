<div style="float: right;">
	<h1 style="padding-left: 280px;"><a href="/<?php echo $base_dir;?>/security_design_requirements/target/<?php echo h($method['Method']['id']);?>" class ="btn btn-primary">Set Security Design Patterns</a></h1>
	<h1 style="margin-top: -44px;"><a href="/<?php echo $base_dir;?>/security_design_requirements/table/<?php echo h($method['Method']['id']);?>" class ="btn btn-primary">Show Security Design Requirements</a></h1>
</div>

<div style="padding-top: 60px;">

<?php if(count($security_design_requirement) > 0): ?>
	<?php for($i = 0; $i < count($security_design_requirement); $i++): ?>
		<div style="padding-bottom: 40px;">
			<?php echo $this->Form->create('Label'); ?>
			<h3 style="padding-bottoom: 60px;">Bind Elements of <span class = "red">"<?php echo h($security_design_requirement[$i]['Pattern']['name']);?>"</span></h3>
			<div class="row">
				<?php for($j = 0; $j < count($security_design_requirement[$i]['Pattern']['PatternElement']); $j++): ?>
					<div class="col-md-4 well element">
						<?php if ($security_design_requirement[$i]['Pattern']['PatternElement'][$j]['is_add'] == 1): ?>
							<p style="text-align: right;">
								<input id="add_countermeasure" type="button" class ="btn btn-success" value="+" />
							</p>
						<?php endif; ?>
						<table class="table table-hover">
							<thead>
								<tr>
									<th><< <?php echo h($security_design_requirement[$i]['Pattern']['PatternElement'][$j]['element']);?> >></th>
								</tr>
							</thead>
							<tbody class="add_countermeasure">
								<tr>
									<td><?php echo $this->Form->input('PatternElement.'.$j.'.id.0', array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $relation, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?></td>
									<td><?php echo $this->Form->hidden('PatternElement.'.$j.'.pattern_element_id', array('value' => $security_design_requirement[$i]['Pattern']['PatternElement'][$j]['id'], )); ?></td>
									<td><?php echo $this->Form->hidden('PatternElement.'.$j.'.security_design_requirement_id', array('value' => $security_design_requirement[$i]['SecurityDesignRequirement']['id'], )); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				<?php endfor; ?>
				<div class="col-md-12">
					<p>
						<?php
						echo $this->Form->submit('Set Binding', array('name' => 'setBind', 'div' => false, 'class' => 'btn btn-primary'));
						?>
					</p>
					<input type="hidden" name="token" value="<?php echo session_id();?>">
					<input type="hidden" name="setBind" value="setBind">
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
		<hr>
	<?php endfor; ?>

<?php else: ?>

	<h3>Patternを選択してください</h3>

<?php endif; ?>

</div>
