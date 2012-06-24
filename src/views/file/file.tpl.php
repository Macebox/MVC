<? if(!$admin): ?>
<h2>You are not authorized to view this content.</h2>

<? else: ?>

<? if (!empty($file['deleted']) || $file['id']==0):?>

<h2>File does not exist.</h2>

<? else: ?>

<h2><?=$file['filename']?>:</h2>

<p><a href="<?=create_controller_url('view/map/'.$file['idMap'])?>">Back</a></p>

<? if($file['type']=='image'): ?>

<a href="<?=base_url().$content?>"><img style="max-width: 100%;" src="<?=base_url().$content?>" /></a>



<? else: ?>

<div class="fileContent smaller-text">
<?=nl2br($content)?>
</div>

<? endif; ?>

<p class="smaller-text">

Last updated: <?=$file['updated']?><br>

<? if($file['type']=='image'): ?>

<a href="javascript:void(prompt('Press Control+C to copy link to image', '<?=base_url().$content?>'));">Copy image</a>

<? else: ?>

<?=create_button(create_controller_url('edit/file/'.$file['id']),"<img src='".base_url('site/data/nocturnal/edit.png')."' />Edit file")?>

<? endif; ?>

</p>

<? endif; ?>

<? endif; ?>