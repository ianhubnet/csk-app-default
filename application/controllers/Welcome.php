<?php

/**
 * Welcome Controller
 *
 * Nothing fancy about this controller, it came with CodeIgniter
 * and we simply rendered a dummy view file. Add your own default
 * controller/module when you start developing.
 *
 * @package    Application\Controllers
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2025, Kader Bouyakoub
 * @since      1.0
 */

final class Welcome extends Public_Controller
{
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// Dummy Hero widget (see `views/welcome.php`)
		$this->add_hero();

		// Render page.
		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * Adds a simple Bootstrap Hero widget to welcome page.
	 *
	 * This is used as an example to show you how you can use `hero` widget.
	 *
	 * @return void
	 */
	private function add_hero()
	{
		// Prepare hero buttons.
		$buttons[] = [
			'icon' => 'book me-2',
			'title' => $this->lang->line('documentation'),
			'href' => Platform::WIKI_URL,
			'attrs' => [
				'class' => 'btn btn-primary',
				'target' => '_blank',
				'rel' => 'noopener'
			],
		];

		// User logged in and as dashboard access.
		if ($this->user?->dashboard) {
			$buttons[] = [
				'icon' => 'dashboard me-2',
				'title' => $this->lang->line('dashboard'),
				'href' => 'admin',
				'attrs' => [
					'class' => 'btn btn-outline-secondary',
					'rel' => 'nofollow'
				],
			];
		}
		// Add login button for visitors
		elseif (!$this->user) {
			$buttons[] = [
				'icon' => 'sign-in-alt me-2',
				'title' => $this->lang->line('login'),
				'href' => 'login',
				'attrs' => ['class' => 'btn btn-outline-secondary'],
			];
		}

		// We queue 'hero' button under 'welcome' slot.
		$this->hub->theme->add_widget('hero', [
			'title' => $this->lang->line('welcome_h1'),
			'subtitle' => $this->lang->line('welcome_p1'),
			'buttons' => $buttons
		], 'welcome');
	}

}
