<? if (!$user->InGroup('admin')): ?>

<h2>You are not authorized to view this content</h2>

<? else: ?>

<h2>Frame Controller</h2>

<span class="smaller-text">

<?=create_button(create_controller_url('create'), "<img src='".base_url('site/data/nocturnal/add.png')."' />Add Frame")?>

<table>
<tr><td>Actions:</td><td>Key:</td><td>Number of contents:</td><td>Created:</td><td>Active:</td></tr>
<? foreach($frames as $frame): ?>

<tr>
<td>
<?=create_button(create_controller_url('edit/'.$frame['id']),"<img src='".base_url('site/data/nocturnal/edit.png')."' />Edit")?>
<?=create_button(create_controller_url('view/'.$frame['id']),"View")?>
</td>
<td><?=$frame['key']?></td>
<td><?=count($frame['content'])?></td>
<td><?=$frame['created']?></td>
<td><?=(empty($frame['deleted'])?'<span class="active">Yes</span>':'<span class="inactive">No</span>')?></td>
</tr>

<? endforeach; ?>

</table>

</span>

<? endif; ?>