ACP

<h2>Admin Control Panel</h2>

<? if($user->InGroup('admin')):?>

<a class="userButton" href="<?=create_controller_url('user')?>">
<img class="userButton" src="<?=base_url('/site/data/nocturnal/user.png')?>" />
<p class="userButton">Users</p>
</a>

<a class="userButton" href="<?=create_controller_url('group')?>">
<img class="userButton" src="<?=base_url('/site/data/nocturnal/group.png')?>" />
<p class="userButton">Groups</p>
</a>

<a class="userButton" href="<?=create_controller_url('route')?>">
<img class="userButton" src="<?=base_url('/site/data/nocturnal/route.png')?>" />
<p class="userButton">Routes</p>
</a>

<a class="userButton" href="<?=create_url('configure/configure')?>">
<img class="userButton" src="<?=base_url('/site/data/nocturnal/configure.png')?>" />
<p class="userButton">Configure</p>
</a>

<a class="userButton" href="<?=create_url('frame')?>">
<img class="userButton" src="<?=base_url('/site/data/nocturnal/frame.png')?>" />
<p class="userButton">Frames</p>
</a>

<a class="userButton" href="<?=create_url('file')?>">
<img class="userButton" src="<?=base_url('/site/data/nocturnal/folder.png')?>" />
<p class="userButton">Files</p>
</a>

<? else: ?>

<p>You are not able to change anything.</p>

<? endif; ?>