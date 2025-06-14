<p>Dear <?= h($user->first_name) ?>  <?= h($user->last_name) ?>,</p>

<p>You can log in to your account using the following link:</p>

<p><a href="<?= $loginLink ?>" target="_blank">Login to your account</a></p>
<p>Username: <?= h($username) ?></p>
<p>Password: <?= h($password) ?></p>

<p>If you did not request this, please contact support.</p>

<p>Best regards,</p>
<p>Your Company Name</p>
