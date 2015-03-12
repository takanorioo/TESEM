<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />


  <?php echo $this->Html->charset(); ?>
  <title><?php echo $title_for_layout; ?></title>
  <meta name="copyright" content="copyright(c)2012 SLOGAN All Rights Reserved.">
  <?php echo $this->Html->meta('description', $description_for_layout); ?>
  <?php echo $this->Html->meta('keywords', $keywords_for_layout); ?>

  <link href="/<?php echo $base_dir;?>/font-awesome/css/font-awesome.css" rel="stylesheet">

  <?php

  echo $this->Html->css(array('bootstrap.min'));
  echo $this->Html->css(array('jquery.ui.all'));


  echo $this->Html->css(array('morris-0.4.3.min'));
  echo $this->Html->css(array('timeline'));
  echo $this->Html->css(array('sb-admin'));
  echo $this->Html->css(array('base'));

  echo $this->fetch('css');

  echo $this->Html->script(array('joint.behaviors'));

  echo $this->Html->script(array('jquery-1.10.2'));
  echo $this->Html->script(array('bootstrap.min'));
  echo $this->Html->script(array('jquery.metisMenu'));
  echo $this->Html->script(array('raphael-2.1.0.min'));
  echo $this->Html->script(array('morris'));
  echo $this->Html->script(array('sb-admin'));

  echo $this->Html->script(array('json2'));
  echo $this->Html->script(array('raphael'));
  echo $this->Html->script(array('joint'));
  echo $this->Html->script(array('joint.arrows'));
  echo $this->Html->script(array('joint.dia'));
  echo $this->Html->script(array('joint.dia.serializer'));
  echo $this->Html->script(array('joint.dia.fsa'));
  echo $this->Html->script(array('joint.dia.uml'));
  echo $this->Html->script(array('joint.dia.pn'));
  echo $this->Html->script(array('joint.dia.devs'));
  echo $this->Html->script(array('joint.dia.cdm'));
  echo $this->Html->script(array('joint.dia.erd'));
  echo $this->Html->script(array('joint.dia.org'));
  echo $this->Html->script(array('framework'));
  echo $this->Html->script(array('jquery.masonry.min'));

  echo $this->Html->script(array('jquery.ui.core.min'));
  echo $this->Html->script(array('jquery.ui.datepicker.min'));
  echo $this->Html->script(array('jquery.ui.datepicker-ja'));


  echo $this->Html->script(array('setlayout'));
  echo $this->Html->script(array('main'));

  echo $this->Html->script(array('blob'));


  

  echo $this->fetch('script');
  ?>

<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/rgbcolor.js"></script> 
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/StackBlur.js"></script>
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/canvg.js"></script> 

  <script>
    $(function() {
      $('.date').datepicker();
    });
  </script>
</head>

<body onload="init()">

  <div id="wrapper">

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">New Project</h4>
          </div>
          <div class ="modal-body">
            <?php echo $this->Form->create('Project', array('controller' => 'Project','action' => 'add')); ?>
            <div class="form-group">
              <fieldset>
                <legend>Projecto Name</legend>
                <?php echo $this->Form->input('Project.name', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Project Name', 'error'=>false)); ?>
              </fieldset>
            </div>
            <div class="form-group">
              <p><?php echo $this->Form->input('Project.open_level', array('label' => false, 'div' => false, 'id' => "radio", 'class' => 'project-botton', 'type' => 'radio', 'options' => array('1'=>'private','2'=>'public'), 'value'=>'1',  'error'=>false)); ?></p>
            </div>
            <input type="hidden" name="token" value="<?php echo session_id();?>">
          </div>
          <div class ="modal-footer">
            <?php
            echo $this->Form->submit('Create', array('name' => 'confirm', 'div' => false, 'class' => 'btn btn-danger'));
            ?>
            <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="selectProject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Please Select Project !</h4>
          </div>
          <div class ="modal-body">
            <?php for($i = 0; $i < count($projects); $i++): ?>
             <div class="form-group">
              <a href="/<?php echo $base_dir;?>/projects/set_project/<?php echo h($projects[$i]['Project']['id']);?>">
                <div>
                  <i class="fa fa-comment fa-fw"></i> <?php echo h($projects[$i]['Project']['name']);?>"
                  <span class="pull-right text-muted small">
                    <?php if ($projects[$i]['Project']['type'] == 1): ?>
                      Private
                    <?php else: ?>
                      Public
                    <?php endif; ?>
                  </span>
                </div>
              </a>
            </div>
          <?php endfor; ?>
          <div class ="modal-footer" style="text-align: center;">
            <a class="myModal" href="#" data-toggle="modal" data-target="#myModal">
              <i class="fa fa-angle-right"></i>
              <strong>Create New Project</strong>              
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>


  <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/<?php echo $base_dir;?>/">
        <?php if (!empty($project_name)): ?>
          Project : <span style="color: indianred;"><?php echo $project_name;?></span>
        <?php else: ?>
          Project : <span style="color: indianred;">Please Select Project !</span>
          <script type="text/javascript">
           $(document).ready(function(){
            $('#selectProject').modal('show');
          });
           $("a.myModal").click(function () {
            console.log("hoge");
            $('#selectProject').modal('hide');
          });
           
         </script>
       <?php endif; ?>
     </a>
   </div>
   <!-- /.navbar-header -->

   <ul class="nav navbar-top-links navbar-right">

    <!-- /.dropdown -->
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-tasks fa-fw"></i> Projects  <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-alerts">

        <?php for($i = 0; $i < count($projects); $i++): ?>
          <li class="divider"></li>
          <li>
            <a href="/<?php echo $base_dir;?>/projects/set_project/<?php echo h($projects[$i]['Project']['id']);?>">
              <div>
                <i class="fa fa-comment fa-fw"></i> <?php echo h($projects[$i]['Project']['name']);?>"
                <span class="pull-right text-muted small">
                  <?php if ($projects[$i]['Project']['type'] == 1): ?>
                    Private
                  <?php else: ?>
                    Public
                  <?php endif; ?>
                </span>
              </div>
            </a>
          </li>
        <?php endfor; ?>
        <li class="divider"></li>
        <li>
          <a class="text-center" href="#" data-toggle="modal" data-target="#myModal">
            <strong>New Project</strong>
            <i class="fa fa-angle-right"></i>
          </a>
        </li>
      </ul>
      <!-- /.dropdown-alerts -->
    </li>

    <!-- /.dropdown -->
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-user fa-fw"></i>
         <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-user">
        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
        </li>
        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
        </li>
        <li class="divider"></li>
        <li><a href="/<?php echo $base_dir;?>/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
        </li>
      </ul>
      <!-- /.dropdown-user -->
    </li>
    <!-- /.dropdown -->
  </ul>
  <!-- /.navbar-top-links -->

  <div class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
      <ul class="nav" id="side-menu">
        <li>
          <a href="/<?php echo $base_dir;?>/">Structre</a>
        </li>
        <li>
          <a href="#">Elements<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <?php for($i = 0; $i < count($elements); $i++): ?>
              <li>
                <a href="/<?php echo $base_dir;?>/element/edit/<?php echo h($elements[$i]['Label']['id']);?>"><span><< <?php echo h($elements[$i]['Label']['interface']);?> >> </span><?php echo h($elements[$i]['Label']['name']);?></a>

              </li>
            <?php endfor; ?>
          </ul>
          <!-- /.nav-second-level -->
        </li>
        <li>
          <a href="/<?php echo $base_dir;?>/element/add">Add Elements</a>
        </li>
        <li>
          <a href="/<?php echo $base_dir;?>/element/target">Set Target Function</a>
        </li>
        <li>
          <a href="/<?php echo $base_dir;?>/element/target_list">Target Function Lists</a>
        </li>
        <li>
          <a href="/<?php echo $base_dir;?>/patterns">Pattern Lists</a>
        </li>
      </ul>
      <!-- /#side-menu -->
    </div>
    <!-- /.sidebar-collapse -->
  </div>
  <!-- /.navbar-static-side -->
</nav>

<div id="page-wrapper">
  <?php echo $this->Session->flash(); ?>
  <?php echo $this->fetch('content'); ?>
</div>
<!-- /#page-wrapper -->

</div>


</body>
</html>