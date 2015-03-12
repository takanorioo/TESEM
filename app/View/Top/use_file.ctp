
-- classes

<?php for($i = 0; $i < count($elements); $i++): ?>
class <?php echo h($elements[$i]['Label']['name']);?>

attributes
<?php if($elements[$i]['Label']['id'] == $label['Label']['id']): ?>
  result : Boolean
<?php endif; ?>
<?php if(!empty($elements[$i]['Attribute'])): ?>
<?php for($j = 0; $j < count($elements[$i]['Attribute']); $j++): ?>
 <?php echo h($elements[$i]['Attribute'][$j]['name']);?> : <?php echo h($elements[$i]['Attribute'][$j]['type']);?>
<?php endfor; ?>
<?php endif; ?>

operations

<?php if(!empty($elements[$i]['Method'])): ?>
<?php for($j = 0; $j < count($elements[$i]['Method']); $j++): ?>
 <?php echo h($elements[$i]['Method'][$j]['name']);?> : Boolean = true
<?php endfor; ?>
<?php endif; ?>
end

<?php endfor; ?>


-- associations

<?php for($i = 0; $i < count($elements); $i++): ?>
<?php for($j = 0; $j < count($elements[$i]['Relation']); $j++)  : ?>
association access_<?php echo h($elements[$i]['Relation'][$j]['id']);?> between
	<?php echo h($elements[$i]['Label']['name']);?>[1..*]
	<?php echo h($elements[$i]['Relation'][$j]['name']);?>[1..*]
end
<?php endfor; ?>
<?php endfor; ?>


-- OCL constraints

constraints

context <?php echo h($label['Label']['name']);?>

  inv SecurityRequirement :
   if
<?php for($i = 0; $i < count($countermeasures); $i++): ?>
<?php if($countermeasures[$i] == '1'): ?>
   self.payment_UI.user.right = true
<?php endif; ?>
<?php endfor; ?>
    self.result = true
  else
    self.result = false
  endif


-- Test Case


<?php for($i = 0; $i < count($elements); $i++): ?>
 !create <?php echo h($elements[$i]['Label']['name']);?> : <?php echo h($elements[$i]['Label']['name']);?>

<?php endfor; ?>


<?php  $attributes_key = array_keys($attributes); ?>
<?php for($i = 0; $i < count($attributes); $i++): ?>
 !set <?php echo h($attributes[$attributes_key[$i]]['name']);?>.<?php echo h($attributes[$attributes_key[$i]]['attribute_name']);?> :=<?php if(!empty($attributes[$attributes_key[$i]]['testcase'])): ?><?php if(is_numeric($attributes[$attributes_key[$i]]['testcase'])): ?><?php echo h($attributes[$attributes_key[$i]]['testcase']);?><?php else: ?>'<?php echo h($attributes[$attributes_key[$i]]['testcase']);?>'
<?php endif; ?>
<?php endif; ?>

<?php endfor; ?>


<?php for($i = 0; $i < count($elements); $i++): ?>
<?php for($j = 0; $j < count($elements[$i]['Relation']); $j++)  : ?>
 !insert (<?php echo h($elements[$i]['Label']['name']);?>, <?php echo h($elements[$i]['Relation'][$j]['name']);?>) into access_<?php echo h($elements[$i]['Relation'][$j]['id']);?>

<?php endfor; ?>
<?php endfor; ?>


 !set <?php echo h($label['Label']['name']);?>.result := <?php echo h($label['Label']['name']);?>.make_a_payment()

