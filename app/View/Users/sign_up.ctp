
<div class="container">
<?php echo $this->Session->flash(); ?>

<!-- Validation -->
<?php if (!empty($this->validationErrors['User']['email'])): ?>
 	<p class ="alert alert-danger"><?php echo $this->validationErrors['User']['email'][0]; ?></p>
<?php endif; ?>
<?php if (!empty($this->validationErrors['User']['user_name'])): ?>
 	<p class ="alert alert-danger"><?php echo $this->validationErrors['User']['user_name'][0]; ?></p>
<?php endif; ?>
<!-- Validation -->

  <h1 class="page-header">Register Profile</h1>
  <div class="row">
    <!-- left column -->
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="text-center" style="margin-top: 30px;margin-left: 50px;">
        <img src="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/img/user/user_0.jpg" class="avatar img-circle img-thumbnail" >
        <?php echo $this->Form->create('Upload', array('type'=>'file', 'enctype' => 'multipart/form-data')); ?>
		<?php echo $this->Form->file('file_name', array('class' => 'text-center center-block well well-sm')) ?>
      </div>
    </div>
    <!-- edit form column -->
    <div class="col-md-8 col-sm-6 col-xs-12 personal-info">
      <h3>Personal info</h3>
      <div class="form-horizontal">
        <div class="form-group">
          <label class="col-lg-3 control-label">First name:</label>
          <div class="col-lg-8">
            <?php echo $this->Form->input('User.first_name', array('label' => false, 'div' => false, 'id' => false, 'type' => 'text', 'class' => 'form-control', 'error'=>false)); ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Last name:</label>
          <div class="col-lg-8">
            <?php echo $this->Form->input('User.last_name', array('label' => false, 'div' => false, 'id' => false, 'type' => 'text', 'class' => 'form-control', 'error'=>false)); ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">UserName:</label>
          <div class="col-lg-8">
            <?php echo $this->Form->input('User.user_name', array('label' => false, 'div' => false, 'id' => false, 'type' => 'text', 'class' => 'form-control', 'error'=>false)); ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Email:</label>
          <div class="col-lg-8">
            <?php echo $this->Form->input('User.email', array('label' => false, 'div' => false, 'id' => false, 'type' => 'text', 'class' => 'form-control', 'error'=>false)); ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Passwrod:</label>
          <div class="col-lg-8">
            <?php echo $this->Form->input('User.password', array('label' => false, 'div' => false, 'id' => false, 'type' => 'password', 'class' => 'form-control', 'error'=>false)); ?>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-3 control-label"></label>
          <div class="col-md-8">
          	<input type="hidden" name="token" value="<?php echo session_id(); ?>">
            <input class="btn btn-primary" value="Register" type="submit">
            <span></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $this->Form->end(); ?>