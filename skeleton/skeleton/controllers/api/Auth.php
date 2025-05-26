<?php
defined('BASEPATH') OR die;

class Auth extends API_Controller
{
	protected function register()
	{}

	// --------------------------------------------------------------------

	protected function activate()
	{}

	// --------------------------------------------------------------------

	protected function resend()
	{}

	// --------------------------------------------------------------------

	protected function restore()
	{}

	// --------------------------------------------------------------------
	// Authentication Methods.
	// --------------------------------------------------------------------

	protected function login()
	{
		// Parse incoming JSON.
		$input = $this->get_json_input();

		// Validate required fields.
		if (empty($input['identity']) OR empty($input['password']))
		{
			$this->response->header = HttpStatusCodes::HTTP_BAD_REQUEST;
			$this->response->message = 'Username/email address and password are required.';
			$this->response->results = $input;
			return;
		}

		// Fetch user from database.
		if ( ! ($user = $this->users->get($input['identity'])))
		{
			$this->response->header = HttpStatusCodes::HTTP_UNAUTHORIZED;
			$this->response->message = 'Unable to find user.';
			return;
		}

		// Verify password.
		isset($this->hash) OR $this->load->library('hash');
		if ( ! $this->hash->check_password($input['password'], $user->password))
		{
			$this->response->header = HttpStatusCodes::HTTP_UNAUTHORIZED;
			$this->response->message = 'Invalid credentials.';
			return;
		}

		// Check if the user is banned, inactive, etc.
		if ($user->deleted !== 0 OR $user->enabled !== 1)
		{
			$this->response->header = HttpStatusCodes::HTTP_FORBIDDEN;
			$this->response->message = 'Account is disabled or deleted.';
			return;
		}

		// Generate tokens.
		$access_token = $this->jwt->generate_access_token($user);

		// Generate a secure refresh token.
		$refresh_token = $this->jwt->generate_refresh_token();

		// Store refresh token in database (user_tokens table).
		// $this->db->insert('user_tokens', array(
		// 	'user_id'      => $user->id,
		// 	'refresh_token'=> $hashed_refresh_token,
		// 	'user_agent'   => $this->input->user_agent(),
		// 	'ip_address'   => $this->input->ip_address(),
		// 	'deleted'      => 0,
		// 	'created_at'   => TIME,
		// 	'updated_at'   => TIME,
		// 	'expired_at'   => $refresh_exp
		// ));

		$results = $this->jwt->save($user->id, array(
			'access_token'  => $access_token,
			'refresh_token' => $refresh_token
		));

		if ( ! $results)
		{
			$this->response->header = HttpStatusCodes::HTTP_NOT_FOUND;
			$this->response->message = 'Something went wrong.';
			return;
		}

		$this->response->header  = HttpStatusCodes::HTTP_OK;
		$this->response->message = 'Login successful.';
		$this->response->results = $results;
	}

	// --------------------------------------------------------------------

	protected function refresh()
	{
		if ( ! ($refresh_token = $this->jwt->parse()))
		{
			$this->response->header = HttpStatusCodes::HTTP_BAD_REQUEST;
			return;
		}
		elseif ( ! ($new = $this->jwt->refresh($refresh_token)))
		{
			$this->response->header = HttpStatusCodes::HTTP_UNAUTHORIZED;
			return;
		}
		else
		{
			$this->response->header = HttpStatusCodes::HTTP_OK;
			$this->response->results = $new;
		}
	}

	// --------------------------------------------------------------------

	protected function verify()
	{}

	// --------------------------------------------------------------------

	protected function recover()
	{}

	// --------------------------------------------------------------------

	protected function reset()
	{}

}
