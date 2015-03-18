<div style="float: right;">
	<h1 style="padding-left: 140px;"><a href="/<?php echo $base_dir;?>/security_design_requirements/target/<?php echo h($method['Method']['id']);?>" class ="btn btn-primary">Select Security Design Patterns</a></h1>
	<h1 style="margin-top: -44px;"><a href="/<?php echo $base_dir;?>/security_design_requirements/bind/<?php echo h($method['Method']['id']);?>" class ="btn btn-primary">Bind Elements</a></h1>
</div>

<h3 style="padding-top: 50px;">▶ Selected Patterns of <span class = "red"> "<?php echo h($method['Method']['name']);?>"</span> process</h3>

<?php if (!empty($security_design_requirement)): ?>
<table class="table table-bordered">
	<thead>
		<tr style="background: lightgray;">
			<th>#</th>
			<td>Patterns</td>
			<td style="width: 230px;">Delete from Selected Pattern</td>
		</tr>
	</thead>
	<tbody>
		<?php for($i = 0; $i < count($security_design_requirement); $i++): ?>
			<tr>
				<td><?php echo $i + 1;?></td>
				<td><?php echo h($security_design_requirement[$i]['Pattern']['name']);?></td>
				<td style="text-align: center;"><a href="/<?php echo $base_dir;?>/security_design_requirements/delete/<?php echo h($security_design_requirement[$i]['SecurityDesignRequirement']['id']);?>" onclick="return confirm('Are You Sure ?');"><img src="/<?php echo $base_dir;?>/img/delete_icon.png" style="margin-top: 5px;"></a></td>
			</tr>	
		<?php endfor; ?>

	</tbody>
</table>
<?php else: ?>
	Please Select Security Desing Patterns
<?php endif; ?>

<h3>▶ Security Design Requirements of <span class = "red"> "<?php echo $method['Method']['name'];?>"</span> process</h3>

<?php if (!empty($security_design_requirement[0]['PatternBind'])): ?>

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

<div style="float: right;">
	<h1 style="padding-left: 160px;"><a href="/<?php echo $base_dir;?>/element/sdr_model_script_data/<?php echo h($method['Method']['id']);?>" class ="btn btn-primary">Create Model Script</a></h1>
	<h1 style="margin-top: -44px;"><a href="/<?php echo $base_dir;?>/element/sdr_testcasedata/<?php echo h($method['Method']['id']);?>" class ="btn btn-primary">Create Test Script</a></h1>
</div>


<?php else: ?>
	Please bind Pattern elemetns
<?php endif; ?>

