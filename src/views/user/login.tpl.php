<h2>Login</h2>
<?=$login_form;?>

<p>

<?php if($allow_create_user): ?>
<a href='<?=create_controller_url('create')?>'>Create a new user</a>
<?php endif; ?>

</p>