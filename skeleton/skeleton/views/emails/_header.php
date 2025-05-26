<?php
defined('BASEPATH') OR die;

/**
 * Default email header template.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Views
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.0
 * @version 	2.0
 */

$email_header = <<<HTML
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{subject}</title>
	<style tyle="text/css">
	a{color: #134d78}
	a:active,a:focus,a:hover{color:#0c314c}
	.btn{padding:8px 16px;background-color:#134d78;border-radius:4px;display:block;width:100%;box-sizing:border-box;color:#fff;margin:4px auto 0;text-decoration:none !important;text-align:center}
	.btn:active,.btn:focus,.btn:hover {background-color:#0c314ccolor:#fff}
	@media screen and (max-width:600px) {
		.content {width:100% !important;display:block !important}
	}
	</style>
</head>
<body style="font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #eee;">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="center" style="padding: 16px;">
				<table class="content" width="600" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: 1px solid #cccccc; background-color: #fff">
					<!-- Header -->
					<tr>
						<td class="header" style="background-color: #f76a00; padding: 16px; text-align: center; color: white; font-size: 24px;">{subject}</td>
					</tr>

					<!-- Body -->
					<tr>
						<td class="body" style="padding: 32px; text-align: left; font-size: 16px; line-height: 1.5;">
HTML;

/**
 * Filter email header.
 * @since 	2.0
 */
echo apply_filters('email_header', $email_header);
