
<div style="float: right;">
	<h1 style="padding-left: 200px;"><a href="/<?php echo $base_dir;?>/security_requirements/bind/<?php echo h($method['Method']['id']);?>" class ="btn btn-primary">Bind Elements</a></h1>
	<h1 style="margin-top: -44px;"><a href="/<?php echo $base_dir;?>/security_requirements/target/<?php echo h($method['Method']['id']);?>" class ="btn btn-primary">Select Countermeasures</a></h1>
</div>


<h3 style="padding-top: 50px;">▶ Selected Countermeasure of <span class = "red"> "<?php echo h($method['Method']['name']);?>"</span> process</h3>

<?php if (!empty($security_requirement)): ?>
<table class="table table-bordered">
	<thead>
		<tr style="background: lightgray;">
			<th>#</th>
			<td>Countermeasure</td>
			<td style="width: 310px;">Delete from Selected Countermeasure</td>
		</tr>
	</thead>
	<tbody>
		<?php for($i = 0; $i < count($security_requirement); $i++): ?>
			<tr>
				<td><?php echo $i + 1;?></td>
				<td><?php echo h($security_requirement[$i]['Countermeasure']['name']);?></td>
				<td style="text-align: center;"><img src="/<?php echo $base_dir;?>/img/delete_icon.png" style="margin-top: 5px;"></td>
			</tr>
		<?php endfor; ?>

	</tbody>
</table>
<?php else: ?>
	Please Select Countermeasures
<?php endif; ?>

<h3>▶ Security Requirements of <span class = "red"> "<?php echo h($method['Method']['name']);?>"</span> process</h3>
<?php if (!empty($security_requirement[0]['CountermeasureBind'])): ?>
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
							<?php $conditions .= "attribute[]="; ?>
							<?php $conditions .= $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['id'];?>
							<?php $conditions .= ".access_right"; ?>
							<?php $conditions .= ".2"; ?>
						<?php endif; ?>

						<!-- I & A -->
						<?php if ($security_requirement[$i-1]['Countermeasure']['id'] == 2): ?>
							<td> <span class = "red">"<?php echo $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['name'];?>"</span> is regular user. </td>
							<?php $conditions .= "attribute[]="; ?>
							<?php $conditions .= $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['id'];?>
							<?php $conditions .= ".regular_user"; ?>
							<?php $conditions .= ".2"; ?>
						<?php endif; ?>

						<!-- Input Data and Validation -->
						<?php if ($security_requirement[$i-1]['Countermeasure']['id'] == 3): ?>
							<td> Use valid input data in <span class = "red">"<?php echo $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['name'];?>"</span>. </td>
							<?php $conditions .= $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['id'];?>
							<?php $conditions .= ".is_user"; ?>
						<?php endif; ?>

					<?php else: ?>

						<!-- Access Control -->
						<?php if ($security_requirement[$i-1]['Countermeasure']['id'] == 1): ?>
							<td> <span class = "red">"<?php echo $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['name'];?>"</span> have access permisson. </td>
							<?php $conditions .= "&"; ?>
							<?php $conditions .= "attribute[]="; ?>
							<?php $conditions .= $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['id'];?>
							<?php $conditions .= ".access_right"; ?>
							<?php $conditions .= ".2"; ?>
						<?php endif; ?>

						<!-- I & A -->
						<?php if ($security_requirement[$i-1]['Countermeasure']['id'] == 2): ?>
							<td> <span class = "red">"<?php echo $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['name'];?>"</span> is regular user. </td>
							<?php $conditions .= "&"; ?>
							<?php $conditions .= "attribute[]="; ?>
							<?php $conditions .= $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['id'];?>
							<?php $conditions .= ".regular_user"; ?>
							<?php $conditions .= ".2"; ?>
						<?php endif; ?>

						<!-- Input Data and Validation -->
						<?php if ($security_requirement[$i-1]['Countermeasure']['id'] == 3): ?>
							<td> Use valid input data in <span class = "red">"<?php echo $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['name'];?>"</span>. </td>
							<?php $conditions .= "&"; ?>
							<?php $conditions .= "attribute[]="; ?>
							<?php $conditions .= $security_requirement[$i-1]['CountermeasureBind'][0]['Label']['id'];?>
							<?php $conditions .= ".is_user"; ?>
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

	<div style="float: right;">
		<h1 style="padding-left: 160px;"><a href="/<?php echo $base_dir;?>/element/sr_model_script_data/<?php echo h($method['Method']['id']);?><?php echo $conditions; ?>" class ="btn btn-primary">Create Model Script</a></h1>
		<h1 style="margin-top: -44px;"><a href="/<?php echo $base_dir;?>/element/sr_testcasedata/<?php echo h($method['Method']['id']);?><?php echo $conditions; ?>" class ="btn btn-primary">Create Test Script</a></h1>
	</div>


<?php else: ?>
	Please Bind Elements
<?php endif; ?>

