<h1>Guestbook</h1><p>Nocturnal's guestbook.</p>

<?=$GuestbookForm?>

<h2>Current messages</h2>
<?php foreach($entries as $entry):?>
<div style='background-color:#f6f6f6;border:1px solid #ccc;margin-bottom:1em;padding:1em;'>
<p>At: <?=$entry['time']?></p>
<p><?=htmlent($entry['text'])?></p>
<p>By: <?=$entry['author']?></p>
</div>
<?php endforeach;?>