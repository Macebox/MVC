<div class='box'>
<h4>All modules</h4>
<p>All Nocturnal modules.</p>
<ul>
<?php foreach($modules as $module): ?>
  <a href="<?=create_url("modules/view/{$module['name']}")?>"><li><?=$module['name']?></li></a>
<?php endforeach; ?>
</ul>
</div>


<div class='box'>
<h4>Nocturnal core</h4>
<p>Nocturnal core modules.</p>
<ul>
<?php foreach($modules as $module): ?>
  <?php if($module['isNocturnalCore']): ?>
	<a href="<?=create_url("modules/view/{$module['name']}")?>"><li><?=$module['name']?></li></a>
  <?php endif; ?>
<?php endforeach; ?>
</ul>
</div>


<div class='box'>
<h4>Nocturnal CMF</h4>
<p>Nocturnal Content Management Framework (CMF) modules.</p>
<ul>
<?php foreach($modules as $module): ?>
  <?php if($module['isNocturnalCMF']): ?>
	<a href="<?=create_url("modules/view/{$module['name']}")?>"><li><?=$module['name']?></li></a>
  <?php endif; ?>
<?php endforeach; ?>
</ul>
</div>


<div class='box'>
<h4>Nocturnal Extra Modules</h4>
<p>Extra modules that belong to Nocturnal.</p>
<ul>
<?php foreach($modules as $module): ?>
  <?php if($module['isNocturnalExtra']): ?>
	<a href="<?=create_url("modules/view/{$module['name']}")?>"><li><?=$module['name']?></li></a>
  <?php endif; ?>
<?php endforeach; ?>
</ul>
</div>

<div class='box'>
<h4>Models</h4>
<p>A class is considered a model if its name starts with CM.</p>
<ul>
<?php foreach($modules as $module): ?>
  <?php if($module['isModel']): ?>
	<a href="<?=create_url("modules/view/{$module['name']}")?>"><li><?=$module['name']?></li></a>
  <?php endif; ?>
<?php endforeach; ?>
</ul>
</div>


<div class='box'>
<h4>Controllers</h4>
<p>Implements interface <code>IController</code>.</p>
<ul>
<?php foreach($modules as $module): ?>
  <?php if($module['isController']): ?>
	<a href="<?=create_url("modules/view/{$module['name']}")?>"><li><?=$module['name']?></li></a>
  <?php endif; ?>
<?php endforeach; ?>
</ul>
</div>

<div class='box'>
<h4>Manageable modules</h4>
<p>Modules that implements the interface IModule.</p>
<ul>
<?php foreach($modules as $module): ?>
  <?php if($module['isManageable']): ?>
	<a href="<?=create_url("modules/view/{$module['name']}")?>"><li><?=$module['name']?></li></a>
  <?php endif; ?>
<?php endforeach; ?>
</ul>
</div>

<div class='box'>
<h4>Forms</h4>
<ul>
<?php foreach($modules as $module): ?>
  <?php if($module['isForm']): ?>
	<a href="<?=create_url("modules/view/{$module['name']}")?>"><li><?=$module['name']?></li></a>
  <?php endif; ?>
<?php endforeach; ?>
</ul>
</div>

<div class='box'>
<h4>More modules</h4>
<p>Modules that does not implement any specific Nocturnal interface.</p>
<ul>
<?php foreach($modules as $module): ?>
  <?php if(!($module['isController'] || $module['isNocturnalCore'] || $module['isNocturnalCMF'] || $module['isManageable'] || $module['isNocturnalExtra'] || $module['isForm'])): ?>
	<a href="<?=create_url("modules/view/{$module['name']}")?>"><li><?=$module['name']?></li></a>
  <?php endif; ?>
<?php endforeach; ?>
</ul>
</div>