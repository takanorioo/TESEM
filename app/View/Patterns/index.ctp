<div style="float: right;">
  <h1 style="padding-left: 280px;">
    <a href="<?php echo FULL_BASE_URL; ?>/patterns/add" class ="btn btn-primary">Add Pattern</a>
  </h1>
</div>

<div class="dashboard" style="padding-top: 40px;"> 
  <section class="content" id="timeline">
    <h2>Public Patterns</h2>
    <div class="stripe">
      <div class="newsfeed">
        <?php if (!empty($patterns)): ?>
          <?php for($i = 0; $i < count($patterns); $i++): ?>
            <section class="entry document">
              <aside>
                  <img src="<?php echo FULL_BASE_URL; ?>/img/user/user_<?php echo h($patterns[$i]['User']['id']);?>.jpg">
              </aside>
              <article>
                <h3><a href="<?php echo FULL_BASE_URL; ?>/patterns/structure/<?php echo h($patterns[$i]['Pattern']['id']);?>"><?php echo h($patterns[$i]['Pattern']['name']);?></a></h3>
                <p style="float: right; padding: 10px;margin-top: -36px;color: gray;">created by : <?php echo h($patterns[$i]['User']['first_name']);?> <?php echo h($patterns[$i]['User']['last_name']);?></p>
                <time><a href="<?php echo FULL_BASE_URL; ?>/patterns/structure/<?php echo h($patterns[$i]['Pattern']['id']);?>">Created : <?php echo h($patterns[$i]['Pattern']['created']);?></a></time>
              </article>
            </section>
          <?php endfor; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>
</div>
