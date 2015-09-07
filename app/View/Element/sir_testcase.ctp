<script type="text/javascript">
	

// masony.jsを使って画像をレンガ状にグリッドする処理
$(window).load(function(){
	$('.row').masonry({
		itemSelector: '.col-md-4',
	});
});


</script>

<div>

	<h3>▶ Security Design Requirements of <span class = "red"> "<?php echo $method['Method']['name'];?>"</span> process</h3>
	<table class="table table-bordered">
		<thead>
			<tr style="background: lightgray;">
				<td colspan="2"></td>
				<?php for($i = 1; $i <= $security_design_requirement_count; $i++): ?>
					<th><?php echo $i;?></th>
				<?php endfor; ?>
			</tr>
		</thead>
		<tbody>
			<?php for($i = 1; $i <= count($security_design_requirement); $i++): ?>
				<tr>
					<?php if ($i == 1): ?>
						<td rowspan="<?php echo count($security_design_requirement);?>">Conditions</td>
					<?php endif; ?>

					<!-- RBAC -->
					<?php if ($security_design_requirement[$i-1]['Pattern']['id'] == 1): ?>
						<td> access permission (<span class = "red">"<?php echo $security_design_requirement[$i-1]['PatternBind'][2]['Label']['name'];?>"</span>) is given in <span class = "red">"<?php echo $security_design_requirement[$i-1]['PatternBind'][1]['Label']['name'];?>"</span> to which an <span class = "red">"<?php echo $security_design_requirement[$i-1]['PatternBind'][3]['Label']['name'];?>"</span> belongs </td>
					<?php endif; ?>

					<!-- Password Design and Use -->
					<?php if ($security_design_requirement[$i-1]['Pattern']['id'] == 2): ?>
						<td> the same ID and Password are inputed into <span class = "red">"<?php echo $security_design_requirement[$i-1]['PatternBind'][2]['Label']['name'];?>"</span> which exist in  <span class = "red">"<?php echo $security_design_requirement[$i-1]['PatternBind'][3]['Label']['name'];?>"</span>  </td>
					<?php endif; ?>



					<?php for($t = 0; $t <  pow (2, $i - 1); $t++): ?>
						<?php for($j = 1; $j <= $security_design_requirement_count/ pow (2, $i); $j++): ?>
							<th>Yes</th>
						<?php endfor; ?>

						<?php for($j = $security_design_requirement_count/ pow (2, $i); $j < $security_design_requirement_count/pow (2, $i - 1) ; $j++): ?>
							<th>No</th>
						<?php endfor; ?>
					<?php endfor; ?>
				</tr>
			<?php endfor; ?>

			<?php for($i = 1; $i <= count($security_design_requirement); $i++): ?>
				<tr>
					<?php if ($i == 1): ?>
						<td rowspan= "<?php echo $td_rowspan;?>">Actions</td>
					<?php endif; ?>

					<!-- RBAC -->
					<?php if ($security_design_requirement[$i-1]['Pattern']['id'] == 1): ?>
						<td> consider that <span class = "red">"<?php echo $security_design_requirement[$i-1]['PatternBind'][4]['Label']['name'];?>"</span> have access permission  </td>
					<?php endif; ?>

					<!-- Password Design and Use -->
					<?php if ($security_design_requirement[$i-1]['Pattern']['id'] == 2): ?>
						<td> <span class = "red">"<?php echo $security_design_requirement[$i-1]['PatternBind'][1]['Label']['name'];?>"</span> is considered regular user </td>
					<?php endif; ?>


					<?php for($t = 0; $t <  pow (2, $i - 1); $t++): ?>
						<?php for($j = 1; $j <= $security_design_requirement_count/ pow (2, $i); $j++): ?>
							<th>×</th>
						<?php endfor; ?>

						<?php for($j = $security_design_requirement_count/ pow (2, $i); $j < $security_design_requirement_count/pow (2, $i - 1) ; $j++): ?>
							<th></th>
						<?php endfor; ?>
					<?php endfor; ?>


				</tr>
				<tr>
					<!-- RBAC -->
					<?php if ($security_design_requirement[$i-1]['Pattern']['id'] == 1): ?>
						<td> consider that <span class = "red">"<?php echo $security_design_requirement[$i-1]['PatternBind'][4]['Label']['name'];?>"</span> dose not have access permission  </td>
					<?php endif; ?>

					<!-- Password Design and Use -->
					<?php if ($security_design_requirement[$i-1]['Pattern']['id'] == 2): ?>
						<td> <span class = "red">"<?php echo $security_design_requirement[$i-1]['PatternBind'][1]['Label']['name'];?>"</span> is considered non-regular user </td>
					<?php endif; ?>

					<?php for($t = 0; $t <  pow (2, $i - 1); $t++): ?>
						<?php for($j = 1; $j <= $security_design_requirement_count/ pow (2, $i); $j++): ?>
							<th></th>
						<?php endfor; ?>

						<?php for($j = $security_design_requirement_count/ pow (2, $i); $j < $security_design_requirement_count/pow (2, $i - 1) ; $j++): ?>
							<th>×</th>
						<?php endfor; ?>
					<?php endfor; ?>
				</tr>
			<?php endfor; ?>

			<tr>

				<td>Execute <span class = "red">"<?php echo $method['Method']['name'];?>"</span> process</td>
				<?php for($i = 1; $i <= $security_design_requirement_count; $i++): ?>
					<?php if ($i == 1): ?>
						<td><?php echo "×";?></td>
					<?php else: ?>
						<td></td>
					<?php endif; ?>
				<?php endfor; ?>
			</tr>
			<tr>
				<td>Not execute <span class = "red">"<?php echo $method['Method']['name'];?>"</span> process</td>
				<?php for($i = 1; $i <= $security_design_requirement_count; $i++): ?>
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
		<?php if(!in_array($elements[$i]['Label']['id'], $non_factor)): ?>
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
		<?php endif; ?>
	<?php endfor; ?>
</div>
<div class="c" style=" padding-top: 40px;padding-bottom: 40px;">
	<p style="text-align: center;padding: 20px;">
		<?php
		echo $this->Form->submit('Crate Test Script', array('name' => 'executeTest', 'div' => false, 'class' => 'btn btn-danger col-md-12'));
		?>
	</p>
	<input type="hidden" name="token" value="<?php echo session_id();?>">
	<input type="hidden" name="executeTest" value="executeTest">
</div>
<?php echo $this->Form->end(); ?>
