
<h1>Elements List</h1>


<table class="table">
  <thead>
    <tr>
      <th>#</th>
      <th>Element</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <?php if (!empty($elements_list)): ?>
    <tbody>
     <?php for($i = 0; $i < count($elements_list); $i++): ?>
      <tr>
        <td><?php echo "";?></td>
        <td><?php echo h($elements_list[$i]['PatternElement']['element']);?></td>
        <td><a href="<?php echo FULL_BASE_URL; ?>/patterns/edit/<?php echo h($elements_list[$i]["PatternElement"]["id"]);?>" class ="btn btn-info">Edit</a></td>
        <td style="text-align: center;"><a href="<?php echo FULL_BASE_URL; ?>/patterns/element_delete/<?php echo h($elements_list[$i]['PatternElement']['id']);?>" onclick="return confirm('Are You Sure ?');"><img src="<?php echo FULL_BASE_URL; ?>/img/delete_icon.png" style="margin-top: 5px;"></a></td>
      </tr>
    <?php endfor; ?>

  </tbody>
<?php endif; ?>
</table>
<div class="row">
	<div class="col-md-12">
		<p style="text-align: right;">
		<a href="<?php echo FULL_BASE_URL; ?>/patterns/add_element/<?php echo h($pattern_id); ?>" class ="btn btn-primary">Add Element</a>
		</p>
	</div>
</div>

