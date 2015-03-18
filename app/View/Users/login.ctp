
<div class="container">
  <img src="/<?php echo $base_dir;?>/img/logo.png" style="width: 250px;">
</div>

<!-- ここからログインヘッダーエリア -->
<div class="login-header">

  <div class="container">
  <?php echo $this->Session->flash(); ?>
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Facebook Login</h3>
          </div>
          <div class="panel-body" >
            <div class="control-group" style="text-align: center;">
              <a href="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/facebook" class ="btn btn-primary" >Login With Facebook</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3" style="padding-top: 10px;">
        <div class="login-panel panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Password Login</h3>
          </div>
          <?php if (isset($this->validationErrors['User']['login'][0])): ?>
            <div class="panel-body" style="text-align: center;">
              <p>
                <span class="red"><?php echo $this->validationErrors['User']['login'][0]; ?></span>
              </p>
            </div>
          <?php endif; ?>
          <div class="panel-body" style="text-align: center;margin-left: 180px;">

            <?php echo $this->Form->create(); ?>
            <fieldset>
              <div class="form-group">
                <?php echo $this->Form->input('User.email', array('id' => 'login_email', 'type' => 'text', 'div' => false, 'label' => false ,'class' => 'col-md-6', 'placeholder' => "email")); ?>
              </div>
              <br>
              <div class="form-group">
               <?php echo $this->Form->input('User.password', array('id' => 'login_passwd', 'type' => 'password', 'div' => false, 'class' => 'col-md-6','label' => false, 'placeholder' => "Password" )); ?>
             </div>
             <br>
             <input type="hidden" name="token" value="<?php echo session_id(); ?>">
             <div class="c">
              <input class="btn btn-info col-md-6" type="submit" value="Login">
            </div>
            <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ここからログインヘッダーエリア -->



  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
          <a href="<?php echo FULL_BASE_URL; ?>/<?php echo $base_dir;?>/sign_up/">
          <div class="panel-heading"style="background: #5cb85c;color: white;">
            <h3 class="panel-title">Sign UP</h3>
          </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ここからログインヘッダーエリア -->




  