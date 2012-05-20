<h1>Content Controller Index</h1>
<p>One controller to manage the actions for content, mainly list, create, edit, delete, view.</p>

<h2>All content</h2>
<?php if($contents != null):?>
  <ul>
  <?php foreach($contents as $val):?>
    <li><p<?php if($val['deleted']!=null) echo ' class="strike"';?>><?=$val['id']?>, <?=$val['title']?> by <?=$val['owner']?><?php if($admin || $val['idUser']==$user['id']):?> <a href='<?=create_url("content/edit/{$val['id']}")?>'>edit</a><?php endif;?></p></li>
  <?php endforeach; ?>
</ul>
<?php else:?>
  <p>No content exists.</p>
<?php endif;?>

<?php if($admin):?>

<h2>Actions</h2>
<ul>
  <li><a href='<?=create_url('content/create')?>'>Create new content</a>
</ul>

<?php endif; ?>