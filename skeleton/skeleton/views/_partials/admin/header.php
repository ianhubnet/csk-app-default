<?php
/**
 * CodeIgniter Skeleton - Administration Header Partial
 * @package     CodeIgniter Skeleton
 * @subpackage  Views
 */
defined('BASEPATH') OR die; ?>
<nav class="navbar fixed-top navbar-expand-lg navbar-admin">
	<div class="container">
		<?php

		/**
		 * Apply filter on the displayed brand on dashboard.
		 * @since   1.4
		 */
		$admin_logo = anchor('admin', null, 'class="navbar-brand skeleton-logo"');
		has_filter('admin_logo') && $admin_logo = apply_filters('admin_logo', $admin_logo);
		if ( ! empty($admin_logo))
		{
			echo $admin_logo;
		}

		?>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-admin" aria-controls="navbar-admin" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbar-admin">
			<ul class="navbar-nav me-auto">
				<?php
				/**
				 * Only homepage for non-admin.
				 * @since 	2.18
				 */
				if ( ! has_action('settings_menu')):
				?>
				<li class="nav-item">
					<?php echo anchor('admin', line('dashboard'), 'class="nav-link"') ?>
				<?php else: ?>
				<li class="nav-item dropdown">
					<?php echo anchor('admin', line('admin_system'), 'class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"') ?>
					<div class="dropdown-menu">
						<?php

						/**
						 * Link to dashboard index.
						 * @since   2.16
						 */
						echo anchor('admin', line('dashboard'), 'class="dropdown-item"');

						/**
						 * Do the actual action.
						 * @since 	2.0
						 */
						do_action('settings_menu');
						?>
					</div><!-- dropdown-menu -->
				</li><!-- nav-item (system) -->
				<?php
				endif; // end of home button check

				/**
				 * Users menu.
				 * Reserved for managers and above.
				 * @since   2.16
				 */
				if (has_action('users_menu')):

				?>
				<li class="nav-item dropdown">
					<?php echo admin_anchor('#', line('admin_users'), 'class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"') ?>
					<div class="dropdown-menu">
						<?php
						/**
						 * Do users_menu action.
						 * @since   2.0
						 */
						do_action('users_menu');
						?>
					</div>
				</li><!--/.nav-item (users)-->
				<?php

				endif; // end of USERS

				/**
				 * Fires right after users dropdown menu.
				 * @since   2.1
				 */
				do_action('admin_navbar');

				/**
				 * Content dropdown menu.
				 * @since   2.0
				 */
				if (has_action('content_menu')):

				?>
				<li class="nav-item dropdown">
					<?php echo admin_anchor('#', line('admin_content'), 'class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"') ?>
					<div class="dropdown-menu">
						<?php
						/**
						 * Do the actual action.
						 * @since   2.0
						 */
						do_action('content_menu');
						?>
					</div>
				</li><!--/.nav-item (content)-->

				<?php

				endif; // end of content dropdown.

				/**
				 * Components dropdown menu.
				 * @since   2.0
				 */
				if (has_action('admin_menu')):

				?>
				<li class="nav-item dropdown">
					<?php echo admin_anchor('#', line('admin_components'), 'class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"') ?>
					<div class="dropdown-menu">
						<?php
						/**
						 * Do the action.
						 * @since   2.0
						 */
						do_action('admin_menu');
						?>
					</div>
				</li><!--/.nav-tem (components)-->
				<?php

				endif; // end of components dropdown

				/**
				 * Extensions menu.
				 * Reserved for admins and above.
				 * @since   2.0
				 */
				if (has_action('extensions_menu')):

				?>
				<li class="nav-item dropdown">
					<?php echo admin_anchor('#', line('admin_extensions'), 'class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"') ?>
					<div class="dropdown-menu">
						<?php
						/**
						 * Do the action.
						 * @since   2.0
						 */
						do_action('extensions_menu');
						?>
					</div>
				</li><!-- nav-link (extensions) -->
				<?php

				endif; // end of EXTENSIONS

				/**
				 * Reports menu.
				 * @since   2.0
				 */
				if (has_action('reports_menu')):

				?>
				<li class="nav-item dropdown">
					<?php echo admin_anchor('#', line('admin_reports'), 'class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"') ?>
					<div class="dropdown-menu">
						<?php
						/**
						 * Do the actual action.
						 * @since   2.0
						 */
						do_action('reports_menu');
						?>
					</div>
				</li>
				<?php

				endif; // end of REPORTS

				/**
				 * Help menu.
				 * @since   2.0
				 */
				if (has_action('help_menu')):

				?>
				<li class="nav-item dropdown">
					<?php echo admin_anchor('#', line('help'), 'class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"') ?>
					<div class="dropdown-menu">
						<?php
						/**
						 * Do the actual action.
						 * @since   2.0
						 */
						do_action('help_menu');
						?>
					</div>
				</li>
				<?php endif; ?>
			</ul>
			<ul class="navbar-nav my-2 my-lg-0"><?php
			/**
			 * Display dashboard notifications.
			 * @since 2.55
			 */
			if (0 < $this->core->num_alerts): ?>
				<li class="nav-item nav-alerts border-end dropdown">
					<a href="javascript:void(0);" class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						<span class="badge bg-danger me-1"><?php echo $this->core->num_alerts ?></span>
						<i class="fa fa-fw fa-bell"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-end dropdown-menu-wide dropdown-menu-sm">
						<?php
						/**
						 * Display list of notifications.
						 * @since 2.55
						 */
						if (0 < $this->core->num_alerts): foreach ($this->core->alerts as $alert):
						?><a class="dropdown-item text-small" href="<?php echo isset($alert['url']) ? $alert['url'] : 'javascript:void(0);' ?>"><?php
							// Alert heading.
							if (isset($alert['title']))
							{
								echo '<span class="nav-alert-title">'.$alert['title'].'</span>';
							}
							// Alert text.
							if (isset($alert['text']))
							{
								echo '<span class="nav-alert-body">'.$alert['text'].'</span>';
							}
						?></a><?php endforeach; endif;
					?></div><!--/.dropdown-menu-->
				</li><!--/.nav-item--><?php
			// End of alerts.
			endif;

				/**
				 * Back to original account.
				 * Used for 'login as' feature.
				 * @since 	2.93
				 */
				if ( ! empty($this->prev_user_id)):
				?>
				<li class="nav-item border-end">
					<?php echo anchor('#', fa_icon('sign-in'), array(
						'class' => 'nav-link',
						'data-form' => esc_url(site_url('switch-account')),
						'data-fields' => 'id:'.$this->prev_user_id,
					)); ?>
				</li>
				<?php
				endif; // end of back to account

				/**
				 * Fires right after users dropdown menu.
				 * @since   2.1
				 */
				do_action('admin_navbar_right');

				/**
				 * Languages dropdown.
				 * @since   1.0
				 */
				if ($this->i18n->polylang):

				?>
				<li class="nav-item dropdown" id="lang-dropdown">
					<a href="javascript:void(0);" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="flag flag-<?php echo $this->i18n->current('flag') ?>" data-text="<?php echo line($this->i18n->current('id')) ?>"></i></a>
					<div class="dropdown-menu dropdown-menu-end dropdown-menu-wide">
						<?php

						/**
						 * List all languages.
						 * @since   1.0
						 */
						$uri_string = esc_url($this->uri_string);
						foreach ($this->i18n->others('current') as $folder => $lang)
						{
							echo anchor(
								'#',
								sprintf(
									'<i class="flag flag-%s" data-text="%s"></i>'.
									'<span class="text-gray float-end ms-2">%s<span>',
									$lang['flag'], line($lang['id']), $lang['name_en']
								),
								// Anchor attributes.
								array(
									'class'       => 'dropdown-item',
									'data-form'   => esc_url($this->config->lang_url($folder)),
									'data-fields' => "idiom:$folder;next:$uri_string",
								)
							);
						}
						unset($uri_string, $folder, $lang);

						?>
					</div>
				</li><!--/.nav-item (languages)-->
				<?php

				endif; //end of Language Switcher

				/**
				 * View site anchor.
				 * @since   2.0
				 */

				?>
				<li class="nav-item csk-view-site"><?php echo anchor(
					'',
					line('admin_view_site').fa_icon('external-link ms-1'),
					'class="nav-link" target="_blank"'
				) ?></li><!--/.nav-item (view site)-->
				<li class="nav-item dropdown border-start">
					<a href="javascript:void(0);" class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="fa fa-user pe-lg-2 ps-lg-2"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-end">
						<?php
						/**
						 * View profile button.
						 * @since 	2.91
						 */
						echo anchor(
							'user/'.$this->user->username,
							line('view_profile'),
							'class="dropdown-item" target="_blank"'
						),

						/**
						 * User settings.
						 * @since 	2.91
						 */
						anchor('admin-profile', line('edit_profile'), 'class="dropdown-item"'),

						// Just a divider.
						'<div class="dropdown-divider"></div>',

						/**
						 * Logout Button.
						 * @since 	2.0
						 */
						anchor('logout', line('logout'), 'class="dropdown-item"');

						?>
					</div>
				</li><!--/.nav-item (user)-->
			</ul>
		</div>
	</div>
</nav>
<header class="header" id="header" role="banner">
	<div class="container clearfix">
		<?php
		/**
		 * Page icon and header.
		 * @since   2.0
		 */
		echo html_tag('h1', array('class' => 'page-title' ), fa_icon($this->page_icon.' me-3', $this->page_title));

		/**
		 * Skeleton logo filter.
		 * @since   2.0
		 */
		if ( ! empty($logo_src = apply_filters('skeleton_logo_src', common_url('img/skeleton.png'))));
		{
			echo admin_anchor('', img($logo_src, array(
				'class' => 'logo',
				'alt'   => $site_name,
			)), 'class="logo-container float-end d-none d-md-block"');
		}

		?>
	</div>
</header>
<?php

/**
 * Subhead section.
 * @since   2.0
 */
if (has_action('admin_submenu') OR isset($this->page_help)):

?>
<div class="submenu clearfix">
	<div class="container">
		<?php

		/**
		 * Fires inside the admin submenu section.
		 * @since   2.0
		 */
		do_action('admin_submenu');

		?>
		<span class="d-flex align-items-end float-end">
		<?php

		/**
		 * Fires inside the right side of the admin's submenu section.
		 * @since   2.16
		 */
		do_action('admin_submenu_right');

		/**
		 * Display help/settings for the current section.
		 * @since   2.0
		 */
		if (isset($this->page_help)): ?>
			<a role="button" href="<?php echo $this->page_help ?>" target="_blank" class="btn btn-info btn-sm">
				<i class="fa fa-fw fa-question-circle"></i><span class="d-none d-md-inline ms-1"><?php _e('help') ?></span>
			</a>
		<?php
		endif; // end of help button

		if (isset($this->page_donate)): ?>
			<a role="button" href="<?php echo $this->page_donate ?>" target="_blank" class="btn btn-olive btn-sm ms-1">
				<i class="fa fa-fw fa-money"></i><span class="d-none d-md-inline ms-1"><?php _e('donate') ?></span>
			</a>
		<?php endif; // end of donate button ?>
		</span>
	</div>
</div>
<?php
endif;
