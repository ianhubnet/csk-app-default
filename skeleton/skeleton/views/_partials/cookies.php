<?php
defined('BASEPATH') OR die;

if (empty($cookies_consent = apply_filters('cookies_consent_text', line('cookie_consent'))))
{
	return;
}

$cookies_consent_uri = apply_filters('cookies_consent_uri', 'page/cookies-policy');
$cookies_consent = sprintf($cookies_consent, $cookies_consent_uri);
?><div class="cookie-consent">
	<p><?php echo $cookies_consent ?></p>
	<div class="d-md-flex gap-md-2">
		<div class="d-grid align-self-start">
			<a href="javascript:void(0);" class="btn btn-success btn-sm btn-accept-cookies"><?php _e('agree') ?></a>
		</div><!--/.col-->
		<div class="d-grid align-self-center mt-2 mt-md-0">
			<a href="javascript:void(0);" class="btn btn-danger btn-sm btn-reject-cookies"><?php _e('reject') ?></a>
		</div><!--/.col-->
		<div class="d-grid flex-fill mt-2 mt-md-0">
			<?php echo anchor($cookies_consent_uri, line('learn_more'), 'class="btn btn-light btn-sm"') ?>
		</div><!--/.col-->
	</div><!--/.row-->
</div><!--/.cookie-consent-->
