<?php

/**
 * Front Static Pages Controller
 *
 * Handles application-level static front-end pages whose content
 * rarely changes (e.g. about, terms, privacy, cookies).
 *
 * These pages are:
 * - Not admin-editable
 * - Language-file driven
 * - Application-specific
 *
 * @package    App\Controllers
 * @category   Public
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2026, Kader Bouyakoub
 * @since      0.0.1
 */

final class Front extends Public_Controller
{
	/**
	 * Layout file to use for method that have output render.
	 *
	 * @var string|null
	 */
	protected $layout = 'clean';

	/**
	 * Methods executed before the requested controller method.
	 *
	 * @param  string $name     Name of the method being dispatched.
	 * @param  mixed  ...$args  Parameters passed to the target method.
	 * @return mixed|null       Return `null` to continue dispatch.
	 */
	protected function before($name, ...$args)
	{
		// 'index' page serves as homepage.
		if ($name === 'index') {
			$this->lang->load('front/home');

			$this->hooks->register('page_header', function () {
				echo $this->hub->theme->widget('home');
			});
		}

		return parent::before($name, ...$args);
	}

	/**
	 * Homepage - Index Page for this controller.
	 *
	 * Since this controller is set as the default controller in
	 * `config/base_controller.php`, it is used as homepage.
	 *
	 * @return void
	 */
	public function index()
	{
		// Example hero widget.
		$this->home_hero();

		// Render page.
		$this->render();
	}

	// --------------------------------------------------------------------
	// Private Methods
	// --------------------------------------------------------------------

	/**
	 * Queues a 'Hero' unit to homepage.
	 *
	 * @return static
	 */
	private function home_hero()
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
			'title' => parse_line('front_home_h1'),
			'subtitle' => parse_line('front_home_p1'),
			'buttons' => $buttons,
			'image' => 'https://i.imgur.com/vlzLdhv.jpg'
		], 'home');
	}

}
