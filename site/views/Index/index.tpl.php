<h1>Index Controller</h1>
<p>This is what you can do for now.</p>

<p>

<ul>
<?php foreach($menu as $val): ?>
<li><a href='<?=create_url($val)?>'><?=$val?></a> 
<?php endforeach; ?>
</ul>

</p>