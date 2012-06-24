<h2>User Index</h2>

<p>

<?php if($is_authenticated): ?>

<a href='<?=create_controller_url('profile')?>'>Profile</a>

<?php else: ?>

<a href='<?=create_controller_url('login')?>'>Login </a>
<?php if($allow_create_user): ?>
<a href='<?=create_controller_url('create')?>'>or create a new user</a>
<?php endif; ?>
<?php endif; ?>

</p>