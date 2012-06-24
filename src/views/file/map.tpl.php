<? if(!$admin): ?>
<h2>You are not authorized to view this content.</h2>

<? else: ?>
<h2>Maps</h2>

<p class="smaller-text">
<?=create_button(create_controller_url('create/map/'.$map),"<img src='".base_url('site/data/nocturnal/add.png')."' />Create new map")?><br>
<?=create_button(create_controller_url('create/file/'.$map),"<img src='".base_url('site/data/nocturnal/add.png')."' />Create new file")?><br>
<?=create_button(create_controller_url('upload/'.$map),"<img src='".base_url('site/data/nocturnal/add.png')."' />Upload file")?>
</p>

<table class="smaller-text">
<tr><td>Map:</td><td></td><td>Edit:</td><td>Remove:</td></tr>
<? if($map!=$root): ?>
<tr><td><a href="<?=create_controller_url('view/map/'.$root)?>">Back</a></td><tr>
<tr><td>&nbsp;</td></tr>
<? endif; ?>
<? foreach($maps as $map): ?>
<? if($map['path']!='/'): ?>
<tr><td><a href="<?=create_controller_url('view/map/'.$map['id'])?>"><?=$map['path']?></a></td><td></td><td><?=create_button(create_controller_url('edit/map/'.$map['id']),"<img src='".base_url('site/data/nocturnal/edit.png')."' />Edit directory")?></td><td><?=create_button(create_controller_url('remove/map/'.$map['id']),"<img src='".base_url('site/data/nocturnal/remove.png')."' />Remove directory")?></td><tr>
<? endif; ?>
<? endforeach; ?>
<tr class="no-border"><td>&nbsp;</td></tr>
<tr><td>File:</td><td>Created:</td><td>Last updated:</td><td>Remove:</td></tr>
<? foreach($files as $file): ?>
<tr><td><a href="<?=create_controller_url('view/file/'.$file['id'])?>"><?=$file['name']?></a></td><td><?=$file['created']?></td><td><?=$file['updated']?></td><td><?=create_button(create_controller_url('remove/file/'.$file['id']),"<img src='".base_url('site/data/nocturnal/remove.png')."' />Remove file")?></td></tr>
<? endforeach; ?>
</table>

<? endif; ?>