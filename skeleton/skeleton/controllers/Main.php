<?php
defined('BASEPATH') OR die;

/**
 * Main Controller
 *
 * This controller handles everything that's public and not a module
 * except for the authentication methods of course.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.16
 */
class Main extends Public_Controller
{
	/**
	 * Array of methods to redirect URI that are used to redirect
	 * users and print demo alert message.
	 * @var 	array
	 */
	protected $demo_protected = array(
		'contact' => 'contact',
		'switch_account' => '',
	);

	/**
	 * Class constructor.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		// switch_account only accessible for admins.
		$this->access_levels['switch_account'] = KB_LEVEL_ADMIN;

		parent::__construct();

		// set level to 0 if admin is on 'switch' mode.
		empty($this->prev_user_id) OR $this->access_levels['switch_account'] = 0;

		// Block direct access to '/main/'.
		$this->_block('main');
	}

	// --------------------------------------------------------------------

	/**
	 * dumb redirect to contact page.
	 * @param 	none
	 * @return 	void
	 */
	public function index()
	{
		redirect('contact');
	}

	// --------------------------------------------------------------------

	/**
	 * Displays the site offline page.
	 *
	 * @param   none
	 * @return  void
	 */
	public function offline()
	{
		/**
		 * If the site isn't really offline or the logged in user is
		 * a manager, we make sure to redirect them to homepage.
		 */
		if ( ! $this->config->item('site_offline') OR $this->auth->is_level(KB_LEVEL_MANAGER))
		{
			redirect('');
		}

		// Filters applied to offline message.
		$this->data['offline_message'] = apply_filters('offline_message', $this->lang->line('site_offline'));

		// Filters applied to offline layout.
		$this->theme
			->set_layout(apply_filters('offline_layout', 'clean'))
			->set_title(apply_filters('offline_title', $this->lang->line('site_offline')))
			->set_meta()
			->render($this->data);
	}

	// --------------------------------------------------------------------

	/**
	 * Handles language switching.
	 *
	 * @param   string  $idiom 	the language to use
	 * @return  void
	 */
	public function switch_language($idiom = null)
	{
		// No language set? nothing to do...
		$idiom = $this->security->xss_clean(empty($idiom) ? $this->input->post_get('idiom') : $idiom);
		if (empty($idiom) OR $idiom === $this->lang->idiom)
		{
			redirect_back('');
		}

		// Change session and user language then redirect.
		$this->i18n->change($idiom, empty($this->prev_user_id));
		redirect_back('');
	}

	// --------------------------------------------------------------------

	/**
	 * Handles account switching.
	 *
	 * @param 	int 	$next_id 	The user's ID to switch to.
	 * @return 	void
	 */
	protected function _switch_account($next_id = null)
	{
		// Grad '$next_id' from request if not provided.
		empty($next_id) && $next_id = $this->input->post_get('id', true);

		/**
		 * Invalid action if:
		 * 	1. The provided user ID is invalid.
		 * 	2. The provided user ID is ours.
		 * 	3. The target user does not exist.
		 */
		if (0 >= $next_id = (int) $next_id
			OR $next_id === $this->user->id
			OR false === ($user = $this->users->get($next_id)))
		{
			redirect('admin/users');
		}
		// Attempt a nested switch?
		elseif ($this->prev_user_id && $this->prev_user_id !== $next_id)
		{
			$this->theme->set_alert($this->lang->line('switch_account_error_nested'), 'error');
			redirect('admin/users');
		}
		// Higher account level?
		elseif ($this->user->level < $user->level && ! $this->prev_user_id)
		{
			$this->theme->set_alert($this->lang->line('switch_account_error_level'), 'error');
			redirect('admin/users');
		}
		// Account locked?
		elseif (-2 == $user->enabled)
		{
			$this->theme->set_alert($this->lang->line('switch_account_error_locked'), 'error');
			redirect('admin/users');
		}
		// Account banned?
		elseif (-1 == $user->enabled)
		{
			$this->theme->set_alert($this->lang->line('account_banned_error'), 'error');
			redirect('admin/users');
		}
		// Account not yet enabled?
		elseif (0 == $user->enabled)
		{
			$this->theme->set_alert($this->lang->line('switch_account_error_disabled'), 'error');
			redirect('admin/users');
		}
		// Account deleted?
		elseif (0 != $user->deleted)
		{
			$this->theme->set_alert($this->lang->line('switch_account_error_deleted'), 'error');
			redirect('admin/users');
		}

		$user_id = $this->user->id;

		// Switching to account?
		if (empty($this->prev_user_id))
		{
			$this->users->update($next_id, array('enabled' => -2));
			$this->prev_user_id = $user_id;
			$this->session->set_userdata(SESS_PREV_USER_ID, $user_id);
		}
		// Going back to account.
		else
		{
			$this->users->update($this->session->userdata(SESS_USER_ID), array('enabled' => 1));
			$this->prev_user_id = null;
			$this->session->unset_userdata(SESS_PREV_USER_ID);
		}

		// Delete variable.
		if ($this->db->delete('variables', array('guid' => $user_id, 'name' => 'online_token')))
		{
			$this->auth->quick_login($user, $this->user->language, null, false);
			redirect('admin/users');
		}

		// Something went wrong?
		$this->theme->set_alert($this->lang->line(array('error_unknown', 'try_again_later')), 'error');
		$this->session->unset_userdata(SESS_PREV_USER_ID);
		redirect('admin/users');
	}

	// --------------------------------------------------------------------

	/**
	 * Handles account disabling process.
	 *
	 * @return 	void
	 */
	protected function _disable_account()
	{
		// User not logged in?
		if ( ! $this->auth->online())
		{
			redirect_back('');
		}
		// Form did not pass CSRF test?
		elseif ($this->nonce->verify_request('account-disable') === false)
		{
			$this->theme->set_alert($this->lang->line('error_csrf'), 'error');
			redirect_back('settings/account');
		}
		// Something went wrong?
		elseif ( ! $this->entities->update($this->user->id, array('enabled' => 0)))
		{
			$this->theme->set_alert($this->lang->line(array('error_unknown', 'try_again_later')), 'error');
			redirect_back('settings/account');
		}
		// All tests passed?
		else
		{
			$this->auth->logout($this->user->id);
			redirect('');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Handles account deletion process.
	 *
	 * @return 	void
	 */
	protected function _delete_account()
	{
		// User not logged in?
		if ( ! $this->auth->online())
		{
			redirect_back('');
		}
		// Form did not pass CSRF test?
		elseif ($this->nonce->verify_request('account-delete') === false)
		{
			$this->theme->set_alert($this->lang->line('error_csrf'), 'error');
			redirect_back('settings/profile');
		}
		// Something went wrong?
		elseif ( ! $this->entities->delete($this->user->id))
		{
			$this->theme->set_alert($this->lang->line(array('error_unknown', 'try_again_later')), 'error');
			redirect_back('settings/profile');
		}
		// All tests passed?
		else
		{
			$this->auth->logout($this->user->id);
			redirect('');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Handles the contact page.
	 *
	 * @param   none
	 * @return  void
	 */
	public function contact()
	{
		// Message rules.
		$rules[] = array(
			'field' => 'message',
			'label' => $this->lang->line('message'),
			'rules' => 'trim|required|min_length[50]'
		);

		if ( ! $this->auth->online())
		{
			// First name
			$rules[] = array(
				'field' => 'first_name',
				'label' => $this->lang->line('first_name'),
				'rules' => 'trim|required|max_length[32]'
			);
			$rules[] = array(
				'field' => 'last_name',
				'label' => $this->lang->line('last_name'),
				'rules' => 'trim|required|max_length[32]'
			);
			$rules[] = array(
				'field' => 'email',
				'label' => $this->lang->line('email'),
				'rules' => 'trim|required|valid_email'
			);
		}
		else
		{
			$first_name = $this->user->first_name;
			$last_name = $this->user->last_name;
			$email = $this->user->email;
		}

		$this->prep_form(apply_filters('contact_rules', $rules), '#contact');

		if ($this->form_validation->run() === false)
		{
			// hidden fields.
			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('user-contact');

			// Prepare form fields.
			if ( ! $this->auth->online())
			{
				$this->data['first_name'] = array_merge(
					$this->config->item('first_name', 'inputs'),
					array('value' => $this->set_value('first_name'))
				);
				$this->data['last_name'] = array_merge(
					$this->config->item('last_name', 'inputs'),
					array('value' => $this->set_value('last_name'))
				);
				$this->data['email'] = array_merge(
					$this->config->item('email', 'inputs'),
					array('value' => $this->set_value('email'))
				);
			}

			$this->data['message'] = array_merge(
				$this->config->item('message', 'inputs'),
				array('value' => $this->set_value('message'))
			);

			// Prepare captcha if enabled.
			empty($captcha = $this->core->captcha()) OR $this->data = array_merge($this->data, $captcha);

			$this->theme
				->set_title($this->lang->line('contact_us'))
				->set_meta()
				->render($this->data);
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('user-contact') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('contact');
		}
		else
		{
			/* Collect fields. */
			isset($first_name) OR $first_name = $this->input->post('first_name', true);
			isset($last_name) OR $last_name  = $this->input->post('last_name', true);
			isset($email) OR $email = $this->input->post('email', true);

			$full_name = sprintf('%s %s', $first_name, $last_name);

			/* Attempt to send the email. */
			$status = $this->core->mail_send(
				array($email, $full_name),
				$this->config->item('contact_email'),
				sprintf('Contact: %s %s', $first_name, $last_name),
				$this->input->post('message', true),
				null,
				$email,
				$full_name
			);

			/* Successfully sent? */
			if (true === $status)
			{
				$this->theme->set_alert($this->lang->line('message_send_success'), 'success');
			}
			/* Something went wrong. */
			else
			{
				$this->theme->set_alert($this->lang->line('message_send_error'), 'error');
			}

			/* Redirect. */
			redirect('contact');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * User profile viewer.
	 *
	 * @param   string  $slug   the username.
	 * @return  void
	 */
	public function user($slug = null)
	{
		if (empty($slug) OR ! ($user = $this->users->get_by('username', sanitize_username($slug, true))))
		{
			redirect('');
		}

		// Only managers can see disabled users.
		if (1 !== $user->enabled && ! $this->auth->is_level(KB_LEVEL_MANAGER))
		{
			redirect('');
		}

		// Only logged in users can see private users.
		if (2 > $user->privacy && ! $this->auth->online())
		{
			redirect('');
		}

		// Only admins can see private profiles.
		if (1 > $user->privacy && $user->id !== $this->auth->user_id() && ! $this->auth->is_level(KB_LEVEL_ADMIN))
		{
			redirect('');
		}

		// Only increment views for profiles that are enabled.
		(1 == $user->enabled && 0 == $user->deleted && $user->id !== $this->auth->user_id()) && $user->incr('views');

		$this->data['user'] = $user;

		$this->theme->set_meta($user)->render($this->data, $user->full_name);
	}

	// --------------------------------------------------------------------
	// ERROR PAGE
	// --------------------------------------------------------------------

	/**
	 * Error 404
	 *
	 * @param 	none
	 * @return 	void
	 */
	public function error_404()
	{
		$this->output->set_status_header(404);
		$this->core->block_404(ip_address());

		$this->data['heading'] = $this->lang->line('error_404');
		$this->data['message'] = $this->lang->sline('error_404_body', $this->uri->uri_string());

		$this->render($this->lang->sline('error_404_uri', $this->uri->uri_string()), array('layout' => 'clean'));
	}

}
