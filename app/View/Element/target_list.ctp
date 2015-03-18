
<h1>Target Function List</h1>


<table class="table">
  <thead>
    <tr>
      <th>#</th>
      <th>Target Function</th>
      <th>Behavior</th>
      <th>Security Requirements</th>
      <th>Security Design Requirements</th>
      <th>Delete</th>
    </tr>
  </thead>
  <?php if (!empty($target_methods['name'])): ?>
    <tbody>
     <?php for($i = 0; $i < count($target_methods['name']); $i++): ?>
      <tr>
        <td><?php echo $i + 1;?></td>
        <td><?php echo h($target_methods['name'][$i]);?></td>
        <td><a href="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/behavior/index/<?php echo h($target_methods['id'][$i]);?>" class ="btn btn-primary">Bahavior</a></td>
        <td><a href="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/security_requirements/table/<?php echo h($target_methods['id'][$i]);?>" class ="btn btn-info">Show Security Requirements</a></td>
        <td><a href="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/security_design_requirements/table/<?php echo h($target_methods['id'][$i]);?>" class ="btn btn-warning">Show Security Design Requirements</a></td>
        <td style="text-align: center;"><a href="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/security_requirements/delete/<?php echo h($target_methods[$i]);?>" onclick="return confirm('Are You Sure ?');"><img src="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/img/delete_icon.png" style="margin-top: 5px;"></a></td>
      </tr>
    <?php endfor; ?>

  </tbody>
<?php endif; ?>

</table>
