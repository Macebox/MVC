<h2>Content Controller Index</h2>

<span class="smaller-text">

<?=create_button(create_controller_url('create'), "<img src='".base_url('site/data/nocturnal/add.png')."' />Add Content")?>

<?php if($contents != null):?>
  <table>
  <tr><td>Actions:</td><td>Key:</td><td>By user:</td><td>Type:</td><td>Filter:</td><td>Created:</td><td>Active:</td></tr>
  <?php foreach($contents as $val):?>
    <tr>
	<td>
	<? if ($authorized): ?>
	<?=create_button(create_controller_url('edit/'.$val['id']),"<img src='".base_url('site/data/nocturnal/edit.png')."' />Edit")?>
	<? endif; ?>
	<?=create_button(create_url('page/view/'.$val['id']),"View")?>
	</td>
	<td><?=$val['key']?></td>
	<td><?=$val['owner']?></td>
	<td><?=$val['type']?></td>
	<td><?=$val['filter']?></td>
	<td><?=$val['created']?></td>
	<td><?=(empty($val['deleted'])?'<span class="active">Yes</span>':'<span class="inactive">No</span>')?></td>
	</tr>
  <?php endforeach; ?>
</table>
<?php else:?>
  No content exists.
<?php endif;?>
  </span>

<?php if($authorized):?>

<?php endif; ?>