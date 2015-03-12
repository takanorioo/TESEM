<div style="float: right;">	
	<h1><a href="/<?php echo $base_dir;?>/security_requirements/table/<?php echo h($method['Method']['id']);?>" class ="btn btn-primary">Show Security Requirements</a></h1>
</div>

<?php for($i = 0; $i < count($security_requirement); $i++): ?>
	<div style="padding-bottom: 40px;">
		<?php echo $this->Form->create('Label'); ?>
		<h3>Bind Elements of <span class = "red">"<?php echo h($security_requirement[$i]['Countermeasure']['name']);?>"</span></h3>
		<div class="row">
			<?php for($j = 0; $j < count($security_requirement[$i]['Countermeasure']['CountermeasureElement']); $j++): ?>
				<div class="col-md-4 well element">
					<p style="text-align: right;">
						<input id="add_countermeasure" type="button" class ="btn btn-success" value="+" />
					</p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th><< <?php echo h($security_requirement[$i]['Countermeasure']['CountermeasureElement'][$j]['element']);?> >></th>
							</tr>
						</thead>
						<tbody class="add_countermeasure">
							<tr>
								<td><?php echo $this->Form->input('CountermeasureElement.'.$j.'.id.0', array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $relation, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?></td>
								<td><?php echo $this->Form->hidden('CountermeasureElement.'.$j.'.countermeasure_element_id', array('value' => $security_requirement[$i]['Countermeasure']['CountermeasureElement'][$j]['id'], )); ?></td>
								<td><?php echo $this->Form->hidden('CountermeasureElement.'.$j.'.security_requirement_id', array('value' => $security_requirement[$i]['SecurityRequirement']['id'], )); ?></td>
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
<?php endfor; ?>


