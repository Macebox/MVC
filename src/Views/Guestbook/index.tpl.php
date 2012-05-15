<h1>Guestbook</h1><p>Nocturnal's guestbook.</p>

<?=$GuestbookForm?>

<h2>Current messages</h2>
<?php foreach($entries as $entry):?>
<div class="gbMsg">
<p class="gbMsgContent"><?=nl2br(htmlent($entry['text']))?></p>
<p class="gbMsgTime"><?=$entry['author']?> @ <?=$entry['time']?></p>
</div>
<?php endforeach;?>