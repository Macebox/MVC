<h1>User Profile</h1>
<ul>
  <li><a href='<?=create_url('user/init')?>'>Init database, create tables and create default admin user</a>
  <li><a href='<?=create_url('user/login/root/root')?>'>Login as root:root (should work)</a>
  <li><a href='<?=create_url('user/login/test@example.com/root')?>'>Login as test@example.com:root (should work)</a>
  <li><a href='<?=create_url('user/login/admin/root')?>'>Login as admin:root (should fail, wrong akronym)</a>
  <li><a href='<?=create_url('user/login/root/admin')?>'>Login as root:admin (should fail, wrong password)</a>
  <li><a href='<?=create_url('user/login/admin@example.com/root')?>'>Login as admin@example.com:root (should fail, wrong email)</a>
  <li><a href='<?=create_url('user/logout')?>'>Logout</a>
</ul>
<p>This is what is known on the current user.</p>

<?php if($is_authenticated): ?>
  <p>User is authenticated.</p>
  <pre><?=print_r($user, true)?></pre>
<?php else: ?>
  <p>User is anonymous and not authenticated.</p>
<?php endif; ?>