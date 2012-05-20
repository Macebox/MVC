<?php if($content['created']): ?>
  <h1>Edit Content</h1>
  <p>You can edit and save this content.</p>
<?php else: ?>
  <h1>Create Content</h1>
  <p>Create new content.</p>
<?php endif; ?>


<?=$form->GetHTML(array('class'=>'content-edit'))?>

<p class="smaller-text">
* The key will be used for routing if the content is of type page. Be aware of this so you don't accidentaly overwrite a controllers route.
</p>

<p class='smaller-text'><em>
<?php if($content['created']): ?>
  This content were created by <?=$content['owner']?> at <?=$content['created']?>.
<?php else: ?>
  Content not yet created.
<?php endif; ?>
</em>
</p>
