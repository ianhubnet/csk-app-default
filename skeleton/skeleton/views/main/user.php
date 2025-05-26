<?php defined('BASEPATH') OR die; ?>
<h1 class="page-title"><?php
/**
 * Display the page's title.
 * @since   2.16
 */
echo sprintf('%s: %s', line('profile'), $user->full_name);
?></h1>
