<?php
defined('BASEPATH') OR die;

/**
 * Default email footer template.
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

/**
 * Email footer powered by text.
 * @since 	2.0
 */
$powered_by = sline('powered_by_url', KPlatform::LABEL, KPlatform::SITE_URL);
$powered_by = apply_filters('email_powered_by', $powered_by);
$year = date('Y');

$email_footer = <<<HTML
						</td>
					</tr>
					<!-- Footer -->
					<tr>
						<td class="footer" style="background-color: #F2F2F2; padding: 16px; text-align: center; color: #333; font-size: 14px;">
							{$powered_by} &#124; {site_name} &copy; {$year}
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
HTML;

/**
 * Filters the default emails footer.
 * @since 	2.0
 */
echo apply_filters('email_footer', $email_footer);
