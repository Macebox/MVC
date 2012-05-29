<h2>Content Controller Index</h2>
<p>One controller to manage the actions for content, mainly list, create, edit, delete, view.</p>

<h3>All content</h3>
<?php if($contents != null):?>
  <ul>
  <?php foreach($contents as $val):?>
    <li><p<?php if($val['deleted']!=null) echo ' class="strike"';?>><?=$val['id']?>, <?=$val['title']?> by <?=$val['owner']?><?php if($admin || $val['idUser']==$user['id']):?> <a href='<?=create_url("content/edit/{$val['id']}")?>'>edit</a><?php endif;?><? if (!$val['deleted'] && $val['type']=='page'):?>&nbsp; &nbsp;<a href="<?=create_url($val['key'])?>">view</a><? endif; ?></p></li>
  <?php endforeach; ?>
</ul>
<?php else:?>
  <p>No content exists.</p>
<?php endif;?>

<?php if($admin):?>

<h4>Actions</h4>
<ul>
  <li><a href='<?=create_url('content/create')?>'>Create new content</a>
</ul>

<?php endif; ?>