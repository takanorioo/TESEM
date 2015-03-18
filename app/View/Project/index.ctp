
<div class="container">
  <?php echo  $this->Session->flash(); ?>

  <!-- Modal -->
  <?php for($i = 0; $i < count($projects); $i++ ): ?>
    <div class="modal fade" id="Modal_<?php echo h($projects[$i]['Project']['id']) ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Invite member</h4>
          </div>
          <div class ="modal-body">
            <?php echo $this->Form->create('Project', array('controller' => 'Project','action' => 'invite')); ?>
            <div class="form-group">
              <fieldset>
                <legend>Enter Member's username</legend>
                <?php echo $this->Form->input('Project.username', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'username', 'error'=>false)); ?>
              </fieldset>
            </div>
            <input type="hidden" name="token" value="<?php echo session_id();?>">
            <input type="hidden" name="project_name" value="<?php echo h($projects[$i]['Project']['id']) ?>">
          </div>
          <div class ="modal-footer">
            <?php
            echo $this->Form->submit('Invite member', array('name' => 'confirm', 'div' => false, 'class' => 'btn btn-danger'));
            ?>
            <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
    </div>
  <?php endfor; ?>



  <div class="row">
    <div class="col-md-10">
      <div style="float: right;">
        <h1 style="padding-left: 280px;"><a href="#" class ="btn btn-primary" data-toggle="modal" data-target="#myModal">Create New Project</a></h1>
      </div>
      <h2>Your Projects</h2>
      <?php if(!empty($projects)): ?>
        <table class="table">
          <thead>
            <tr>
              <th>Project Name</th>
              <th>State</th>
              <th>Menber</th>
              <th>Invite member</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php for($i = 0; $i < count($projects); $i++ ): ?>
              <tr>
                <td><a href="/<?php echo $base_dir;?>/projects/set_project/<?php echo h($projects[$i]['Project']['id']) ?>"><?php echo h($projects[$i]['Project']['name']) ?></a></td>
                <td>
                  <?php if ($projects[$i]['Project']['type'] == 1): ?>
                    Private
                  <?php else: ?>
                    Public
                  <?php endif; ?>
                </td>

                <td style="width: 280px;">
                  <img src="/<?php echo $base_dir;?>/img/user/user_<?php echo $projects[$i]['Project']['user_id']; ?>.jpg" class="avatar img-thumbnail" style="width: 50px;">
                  <?php for($j = 0; $j < count($projects[$i]['UsersProject']); $j++ ): ?>
                    <img src="/<?php echo $base_dir;?>/img/user/user_<?php echo $projects[$i]['UsersProject'][$j]['user_id']; ?>.jpg" class="avatar img-thumbnail" style="width: 50px;">
                  <?php endfor; ?>
                  <td>
                    <a class="btn btn-primary"  href="#" data-toggle="modal" data-target="#Modal_<?php echo h($projects[$i]['Project']['id']) ?>">Invite member</a></td>
                    <td><a href="/<?php echo $base_dir;?>/projects/delete/<?php echo h($projects[$i]['Project']['id']) ?>" class = "btn btn-danger">Delete</a></td>
                  </tr>
                <?php endfor; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-10">
          <?php if(!empty($invited_projects)): ?>
            <h2>Invited Projects</h2>
            <table class="table ">
              <thead>
                <tr>
                  <th>Project Name</th>
                  <th>State</th>
                  <th>Menber</th>
                </tr>
              </thead>
              <tbody>
                <?php for($i = 0; $i < count($invited_projects); $i++ ): ?>
                  <tr>
                    <td><a href="/<?php echo $base_dir;?>/projects/set_project/<?php echo h($invited_projects[$i]['Project']['id']) ?>"><?php echo h($invited_projects[$i]['Project']['name']) ?></a></td>
                    <td>
                      <?php if ($invited_projects[$i]['Project']['type'] == 1): ?>
                        Private
                      <?php else: ?>
                        Public
                      <?php endif; ?>
                    </td>
                    <td style="width: 280px;">
                      <?php for($j = 0; $j < count($invited_projects[$i]['Project']['Member']); $j++ ): ?>
                        <img src="/<?php echo $base_dir;?>/img/user/user_<?php echo $invited_projects[$i]['Project']['Member'][$j]; ?>.jpg" class="avatar img-thumbnail" style="width: 50px;">
                      <?php endfor; ?>
                      <td>
                      </tr>
                    <?php endfor; ?>
                  </tbody>
                </table>
              <?php endif; ?>
            </div>
          </div>

        </div>
