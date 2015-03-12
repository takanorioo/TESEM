<script type="text/javascript">
	

// masony.jsを使って画像をレンガ状にグリッドする処理
$(window).load(function(){
	$('.row').masonry({
		itemSelector: '.col-md-4',
	});
});


</script>

<div>
	<h3>▶ Security Requirements of <span class = "red"> "<?php echo h($method['Method']['name']);?>"</span> process</h3>
	<table class="table table-bordered">
		<thead>
			<tr style="background: lightgray;">
				<td colspan="2"></td>
				<?php for($i = 1; $i <= $security_requirement_count; $i++): ?>
					<th>
						<?php echo $i;?>
					</th>
				<?php endfor; ?>
			</tr>
		</thead>
		<tbody>
			<?php for($i = 1; $i <= count($security_requirement); $i++): ?>
				<tr>
					<?php if ($i == 1): ?>
						<td rowspan="<?php echo count($security_requirement);?>">Conditions</td>

						<!-- Access Control -->
						<?php if ($security_requirement[$i-1]['Countermeasure']['id'] == 1): ?>
							<td> <span class = "red">"<?php echo $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['name'];?>"</span> have access permisson. </td>
						<?php endif; ?>

						<!-- I & A -->
						<?php if ($security_requirement[$i-1]['Countermeasure']['id'] == 2): ?>
							<td> <span class = "red">"<?php echo $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['name'];?>"</span> is regular user. </td>
						<?php endif; ?>

						<!-- Input Data and Validation -->
						<?php if ($security_requirement[$i-1]['Countermeasure']['id'] == 3): ?>
							<td> Use valid input data in <span class = "red">"<?php echo $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['name'];?>"</span>. </td>>
						<?php endif; ?>

					<?php else: ?>

						<!-- Access Control -->
						<?php if ($security_requirement[$i-1]['Countermeasure']['id'] == 1): ?>
							<td> <span class = "red">"<?php echo $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['name'];?>"</span> have access permisson. </td>
						<?php endif; ?>

						<!-- I & A -->
						<?php if ($security_requirement[$i-1]['Countermeasure']['id'] == 2): ?>
							<td> <span class = "red">"<?php echo $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['name'];?>"</span> is regular user. </td>
						<?php endif; ?>

						<!-- Input Data and Validation -->
						<?php if ($security_requirement[$i-1]['Countermeasure']['id'] == 3): ?>
							<td> Use valid input data in <span class = "red">"<?php echo $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['name'];?>"</span>. </td>
						<?php endif; ?>

					<?php endif; ?>


					<?php for($t = 0; $t <  pow (2, $i - 1); $t++): ?>
						<?php for($j = 1; $j <= $security_requirement_count/ pow (2, $i); $j++): ?>
							<th>Yes</th>
						<?php endfor; ?>

						<?php for($j = $security_requirement_count/ pow (2, $i); $j < $security_requirement_count/pow (2, $i - 1) ; $j++): ?>
							<th>No</th>

						<?php endfor; ?>
					<?php endfor; ?>
				</tr>
			<?php endfor; ?>

			<tr>
				<td rowspan="2">Actions</td>
				<td>Execute <span class = "red">"<?php echo $method['Method']['name'];?>" </span> process</td>
				<?php for($i = 1; $i <= $security_requirement_count; $i++): ?>
					<?php if ($i == 1): ?>
						<td><?php echo "×";?></td>
					<?php else: ?>
						<td></td>
					<?php endif; ?>
				<?php endfor; ?>
			</tr>
			<tr>
				<td>Not execute <span class = "red"> "<?php echo $method['Method']['name'];?>"</span> process</td>
				<?php for($i = 1; $i <= $security_requirement_count; $i++): ?>
					<?php if ($i != 1): ?>
						<td><?php echo "×";?></td>
					<?php else: ?>
						<td></td>
					<?php endif; ?>
				<?php endfor; ?>
			</tr>
		</tbody>
	</table>
</div>


<?php echo $this->Form->create('Label', array('id' => false)); ?>
<div class="row"style = "padding-top:40px">
	<?php for($i = 0; $i < count($elements); $i++): ?>
		<?php if(!empty($elements[$i]['Attribute'])): ?>
			<div class="col-md-4 well element">
				<?php echo h($elements[$i]['Label']['name']);?>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Name (Type)</th>
							<th>Test Case</th>
						</tr>
					</thead>
					<?php for($j = 0; $j < count($elements[$i]['Attribute']); $j++): ?>
						<tbody>
							<tr>
								<td><?php echo h($elements[$i]['Attribute'][$j]['name']);?>  (<?php echo $TYPE[$elements[$i]['Attribute'][$j]['type']];?>)</td>
								<?php if($elements[$i]['Attribute'][$j]['type'] == 0): ?>
									<td><?php echo $this->Form->input('Attribute.name.'.$elements[$i]['Attribute'][$j]['id'].'.testcase', array('label' => false, 'div' => false, 'id' => $elements[$i]['Attribute'][$j]['id'], 'type' => 'select', 'options' => $INTCASE, 'class' => 'form-control', 'error'=>false)); ?>
									</td>
								<?php elseif($elements[$i]['Attribute'][$j]['type'] == 1): ?>
									<td><?php echo $this->Form->input('Attribute.name.'.$elements[$i]['Attribute'][$j]['id'].'.testcase', array('label' => false, 'div' => false, 'id' => $elements[$i]['Attribute'][$j]['id'], 'type' => 'text', 'class' => 'form-control', 'error'=>false)); ?>
									</td>
								<?php elseif($elements[$i]['Attribute'][$j]['type'] == 2): ?>
									<td><?php echo $this->Form->input('Attribute.name.'.$elements[$i]['Attribute'][$j]['id'].'.testcase', array('label' => false, 'div' => false, 'id' => $elements[$i]['Attribute'][$j]['id'], 'type' => 'select', 'options' => $BOOLCASE, 'class' => 'form-control', 'error'=>false)); ?>
									</td>	
								<?php else: ?>
									<td><?php echo $this->Form->input('Attribute.name.'.$elements[$i]['Attribute'][$j]['id'].'.testcase', array('label' => false, 'div' => false, 'id' => $elements[$i]['Attribute'][$j]['id'], 'type' => 'text', 'class' => 'form-control date','placeholder' => 'Date',  'error'=>false)); ?>
									</td>
								<?php endif; ?>
							</tr>
						</tbody>
					<?php endfor; ?>
				</table>
			</div>
		<?php endif; ?>
	<?php endfor; ?>
</div>
<div class="c" style=" padding-top: 40px;padding-bottom: 40px;">
	<p style="text-align: center;padding: 20px;">
		<?php
		echo $this->Form->submit('Test Generate', array('name' => 'executeTest', 'div' => false, 'class' => 'btn btn-danger col-md-12'));
		?>
	</p>
	<input type="hidden" name="token" value="<?php echo session_id();?>">
	<input type="hidden" name="executeTest" value="executeTest">
</div>
<?php echo $this->Form->end(); ?>