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

<!--
<?php echo $this->Form->create('Label', array('id' => false)); ?>
-->
<?php echo $this->Form->create('Element', array('id' => false, 'enctype' => 'multipart/form-data', 'type'=>'file')); ?>
<div>
        <div>
                <div>
                        <div class="row" style = "padding-top:40px">
                                <!--<div class="col-md-8 well">-->
                                <div class="well">
							        <table class="table table-hover">
                                                <thead>
														<?php ?>
                                                        <tr>
                                                                <th>Pattern</th>
                                                                <th>Test Case</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
														<?php for($i=0;$i<$sdr_count;$i++): ?>
														<tr>
                                                                <td><?php echo $security_design_requirement[$i]['Pattern']['name'];?></td>
																<td><div class="col-s-3">
																<?php for($n=0;$n<count($input_field[$i]);$n++): ?>
	                                                                <?php echo $input_field[$i][$n];?>
																<?php endfor; ?>
																</div></td>
                                                        </tr>
														<?php endfor; ?>
                                                </tbody>
                                        </table>

                                </div>

                                <div class="well">
                                        <?php echo $this->Form->input('Input Selenium Test Case', array('type'=>'file' )); ?>
                                </div>
                                <div class="well">
                                        <table class="table table-hover">
                                                <thead>
                                                        <tr>
                                                                <th>Temporary File Path</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                        <tr>
                                                                <td><?php echo $this->Form->input('Temp.path', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'File Path(e.g. C:\tmp.txt)', 'error'=>false)); ?></td>
                                                        </tr>
                                                </tbody>
                                        </table>
                                </div>
                                <div class="col-md-12">
                                        <p style="text-align: center;">
                                                <?php
						echo $this->Form->submit('Crate Test Script', array('name' => 'executeTest', 'div' => false, 'class' => 'btn btn-danger col-md-12'));
                                                ?>
                                        </p>
                                        <input type="hidden" name="token" value="<?php echo session_id();?>">
					<input type="hidden" name="executeTest" value="executeTest">
                                </div>
                                <?php echo $this->Form->end(); ?>
                        </div>
                </div>
        </div>
</div>
