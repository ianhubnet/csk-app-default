<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_jwt Class
 *
 * A compact but complete JWT system made for CI Skeleton.
 * Includes encode/decode, refresh token generation, blacklist, etc.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2025, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.134
 */
final class Kbcore_jwt extends KB_Driver
{
	/**
	 * The algorithm used to sign the token.
	 *
	 * @var string
	 */
	protected $algo = 'HS256';

	/**
	 * Time to live (TTL) for access tokens, in seconds.
	 *
	 * @var int
	 */
	protected $ttl = MINUTE_IN_SECONDS * 15;

	/**
	 * Time to refresh (TTR) for refresh tokens, in seconds.
	 *
	 * @var int
	 */
	protected $ttr = WEEK_IN_SECONDS; // 1 week.

	/**
	 * Cached decoded payload after token validation.
	 *
	 * @var array|false
	 */
	protected $_payload;

	// --------------------------------------------------------------------

	/**
	 * Encodes a payload array into a JWT string.
	 *
	 * @param 	array 		$payload	Custom payload data.
	 * @param 	int|null 	$ttl 		Optional custom TTL.
	 *
	 * @return	string	The JWT string.
	 */
	public function encode(array $payload = array(), int $ttl = null)
	{
		// Add audience, issuer, issued at and expiration.
		isset($payload['aud']) OR $payload['aud'] = $this->_parent->is_mobile ? 'mobile-app' : 'web-app';
		isset($payload['iss']) OR $payload['iss'] = $this->ci->config->item('domain_url');
		isset($payload['iat']) OR $payload['iat'] = TIME;
		isset($payload['exp']) OR $payload['exp'] = TIME + ($ttl ?? $this->ttl);

		// Prepare token header.
		$header = array('alg' => $this->algo, 'typ' => 'JWT');

		// Prepare token segments.
		$segments = array(
			$this->base64_url_encode(json_encode($header)),
			$this->base64_url_encode(json_encode($payload))
		);

		// Generate token signature and add it to segments.
		$signature = $this->sign(implode('.', $segments));
		$segments[] = $signature;

		// Return final string.
		return implode('.', $segments);
	}

	// --------------------------------------------------------------------

	/**
	 * Decodes and verifies a JWT string.
	 *
	 * @param 	string 	$token 		The JWT string.
	 * @param 	bool 	$verify_exp Whether to check for expiration.
	 *
	 * @return 	array|false 	Decoded payload array on success, false on failure.
	 */
	public function decode(string $token, bool $verify_exp = true)
	{
		// Is the token blacklisted?
		if ($this->is_blacklisted($token))
		{
			return $this->_payload = false;
		}
		// Doesn't it have 3 parts?.
		elseif (count($parts = explode('.', $token)) !== 3)
		{
			return $this->_payload = false;
		}

		// Prepare header, payload and signature strings.
		[$header64, $payload64, $signature] = $parts;

		// Hashes aren't equal?
		if ( ! hash_equals($expected = $this->sign("{$header64}.{$payload64}"), $signature))
		{
			return $this->_payload = false;
		}
		// Something is wrong with JSON string?
		elseif ( ! ($this->_payload = json_decode($this->base64_url_decode($payload64), true)))
		{
			return $this->_payload = false;
		}
		// Token expired?
		elseif ($verify_exp && isset($this->_payload['exp']) && TIME > $this->_payload['exp'])
		{
			return $this->_payload = false;
		}

		// Everything passed...
		return $this->_payload;
	}

	// --------------------------------------------------------------------

	/**
	 * Invalidates a given JWT by blacklisting it.
	 *
	 * @param 	string 	$token 	The token to blacklist.
	 *
	 * @return 	bool 	True if successful, false otherwise.
	 */
	public function invalidate(string $token)
	{
		return $this->blacklist($token);
	}

	// --------------------------------------------------------------------

	/**
	 * Signs a string with HMAC SHA-256.
	 *
	 * @param 	string 	$data 	The data to sign.
	 *
	 * @return 	string 	The base64 URL-encoded HMAC signature.
	 */
	public function sign($data)
	{
		return $this->base64_url_encode(hash_hmac('sha256', $data, $this->ci->config->item('encryption_key_256'), true));
	}

	// --------------------------------------------------------------------

	/**
	 * Checks if a token is blacklisted.
	 *
	 * @param 	string 	$token 	The JWT token.
	 *
	 * @return 	bool 	True if blacklisted, false otherwise.
	 */
	public function is_blacklisted(string $token)
	{
		return array_key_exists($token, $this->ci->config->item('jwt_blacklist', null, array()));
	}

	// --------------------------------------------------------------------

	/**
	 * Adds a token to the blacklist.
	 *
	 * @param 	string 	$token 	The token to blacklist.
	 *
	 * @return 	bool 	True on success, false on failure.
	 */
	public function blacklist(string $token)
	{
		$blacklist = $this->ci->config->item('jwt_blacklist', null, array());
		$blacklist[$token] = TIME;

		return $this->_parent->options->set_item('jwt_blacklist', $blacklist);
	}

	// --------------------------------------------------------------------

	/**
	 * Attempts to extract a JWT from the request.
	 *
	 * @return 	string|null 	The token if found, or null.
	 */
	public function parse()
	{
		// From HTTP header?
		$header = $this->ci->input->get_request_header('Authorization', true);
		if ($header && preg_match('/Bearer\s(\S+)/', $header, $matches))
		{
			return $matches[1];
		}
		// From request?
		elseif ( ! empty($token = $this->ci->input->get_post('bearer', true)))
		{
			return $token;
		}
		// Nothing found!
		else
		{
			return null;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Gets the decoded payload of a JWT.
	 *
	 * @param 	string|null 	$token 		Optional token (falls back to parsed token).
	 * @param 	bool 			$verify_exp	Whether to check for expiration.
	 *
	 * @return 	array|false 	Decoded payload, or false if invalid.
	 */
	public function payload(?string $token = null, bool $verify_exp = true)
	{
		// Already cached?
		if (isset($this->_payload))
		{
			return $this->_payload;
		}

		// Missing token? Parse it...
		empty($token) && $token = $this->parse();

		// Nothing found?
		if (empty($token))
		{
			return $this->_payload = false;
		}
		// Could not be decode?
		elseif ( ! ($this->_payload = $this->decode($token, $verify_exp)))
		{
			return $this->_payload = false;
		}

		// Everything passed.
		return $this->_payload;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves the subject (user ID) from the payload.
	 *
	 * @param 	string|null 	$token 		Optional token.
	 * @param 	mixed 			$default 	Default return value if not found.
	 *
	 * @return 	mixed 	Subject from token or default.
	 */
	public function subject(?string $token = null, $default = 0)
	{
		$payload = $this->payload($token);

		return (is_array($payload) && isset($payload['sub'])) ? $payload['sub'] : $default;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves the audience from the payload.
	 *
	 * @param 	string|null 	$token 		Optional token.
	 * @param 	mixed 			$default 	Default value if not found.
	 *
	 * @return 	mixed 	Audience value or default.
	 */
	public function audience(?string $token = null, $default = 'web-app')
	{
		$payload = $this->payload($token);

		return (is_array($payload) && isset($payload['aud'])) ? $payload['aud'] : $default;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves the issuer from the payload.
	 *
	 * @param 	string|null 	$token 		Optional token.
	 * @param 	mixed 			$default 	Default value if not found.
	 *
	 * @return 	mixed 	Issuer or default.
	 */
	public function issuer(?string $token = null, $default = KB_SHORT)
	{
		$payload = $this->payload($token);

		return (is_array($payload) && isset($payload['iss'])) ? $payload['iss'] : $default;
	}

	// --------------------------------------------------------------------

	/**
	 * Saves a hashed version of access and refresh tokens to the database.
	 *
	 * @param 	int 	$user_id 		User ID to associate with tokens.
	 * @param 	array 	$token_data 	Token data (access/refresh/user_agent/IP).
	 *
	 * @return 	bool|array 	Array of token data on success,, false otherwise.
	 */
	public function save(int $user_id, array $token_data): bool|array
	{
		// Make sure the user exists first.
		if ( ! ($user = $this->_parent->users->get($user_id)))
		{
			return false;
		}

		// Load `Hash` library if not already loaded.
		isset($this->ci->hash) OR $this->ci->load->library('hash');

		// Prepare data to insert into `tokens` table.
		$data = array(
			'user_id'            => $user_id,
			'access_token'       => $token_data['access_token'],
			'access_expires_at'  => TIME + $this->ttl,
			'refresh_token'      => $this->ci->hash->hash_password($token_data['refresh_token']),
			'refresh_expires_at' => TIME + $this->ttr,
			'user_agent'         => $this->ci->input->user_agent(),
			'ip_address'         => ip_address(),
			'revoked'            => 0,
			'revoked_at'         => 0,
			'last_used_at'       => TIME,
			'created_at'         => TIME,
			'updated_at'         => TIME
		);

		// Something went wrong?
		if ( ! $this->ci->db->insert('tokens', $data))
		{
			return false;
		}

		// Complete token data before returning it.
		$token_data['access_expire']  = $data['access_expires_at'];
		$token_data['refresh_expire'] = $data['refresh_expires_at'];
		$token_data['token_type']     = 'Bearer';
		$token_data['current_time']   = TIME;
		$token_data['access_ttl']     = $this->ttl;
		$token_data['refresh_ttl']    = $this->ttr;
		$token_data['user']           = array(
			'id'         => $user->id,
			'email'      => $user->email,
			'first_name' => $user->first_name,
			'last_name'  => $user->last_name,
			'gender'     => $user->gender
		);

		return $token_data;
	}

	// --------------------------------------------------------------------

	/**
	 * Revokes a refresh token or all tokens by user ID.
	 *
	 * @param 	string|int 	$target 	Refresh token (string) or user ID (int).
	 *
	 * @return 	int 	Number of affected rows.
	 */
	public function revoke(string|int $target): int
	{
		// A string is passed? We revoke the targeted access token.
		if (is_string($target))
		{
			$this->ci->db
				->where('BINARY(access_token)', $target)
				->set('revoked', 1)
				->set('revoked_at', TIME)
				->update('tokens');

			return $this->ci->db->affected_rows();
		}

		//Revoke all the given user's tokens.
		$this->ci->db
			->where('user_id', $target)
			->set('revoked', 1)
			->set('revoked_at', TIME)
			->update('tokens');

		return $this->ci->db->affected_rows();
	}

	// --------------------------------------------------------------------

	/**
	 * Refreshes an access token using a valid refresh token.
	 *
	 * Searches for a valid, non-revoked refresh token in the database,
	 * verifies it using password hashing, and if matched, generates and returns
	 * a new pair of access and refresh tokens. The old token is revoked,
	 * and the new one is stored along with user agent and IP address.
	 *
	 * @param 	string 	$refresh_token 	The refresh token provided by the client.
	 *
	 * @return 	array|null 	Returns a new token payload with user info on success, or null on failure.
	 */
	public function refresh(string $refresh_token)
	{
		// We run the query to make sure we have tokens to check first.
		$query = $this->ci->db
			->where('refresh_token !=', null)
			->where('refresh_expires_at >', TIME)
			->where('revoked', 0)
			->get('tokens');

		// No tokens found? Skip.
		if ($query->num_rows() <= 0)
		{
			return null;
		}

		// Make sure to load `Hash` library if not already loaded.
		isset($this->ci->hash) OR $this->ci->load->library('hash');

		// Prepare the tokens list and loop through them.
		$tokens = $query->result();
		foreach ($tokens as $token)
		{
			// Found one?
			if ($this->ci->hash->check_password($refresh_token, $token->refresh_token))
			{
				// Make sure the user exists before proceeding.
				if ( ! ($user = $this->_parent->users->get($token->user_id)))
				{
					// Just delete all tokens of that user, they are useless.
					$this->ci->db->delete('tokens', array('user_id' => $token->user_id));
					return null;
				}

				// We make sure to revoke the old token.
				$this->ci->db
					->where('id', $token->id)
					->set('revoked', 1)
					->set('revoked_at', TIME)
					->update('tokens');

				// Let's now generate new `access_token` and `refresh_token`,
				// then prepare the data to be update.
				$new_access_token = $this->generate_access_token($user);
				$new_refresh_token = $this->generate_refresh_token();

				// Prepare the data to update into table.
				$data = array(
					'access_token'       => $new_access_token,
					'access_expires_at'  => TIME + $this->ttl,
					'refresh_token'      => $this->ci->hash->hash_password($new_refresh_token),
					'refresh_expires_at' => TIME + $this->ttr,
					'user_agent'         => $this->ci->input->user_agent(),
					'ip_address'         => ip_address(),
					'revoked'            => 0,
					'revoked_at'         => 0,
					'last_used_at'       => TIME,
					'updated_at'         => TIME
				);

				if ( ! $this->ci->db->update('tokens', $data, array('id' => $token->id)))
				{
					return false;
				}

				return array(
					'access_token'   => $new_access_token,
					'refresh_token'  => $new_refresh_token,
					'access_expire'  => $data['access_expires_at'],
					'refresh_expire' => $data['refresh_expires_at'],
					'token_type'     => 'Bearer',
					'current_time'   => TIME,
					'access_ttl'     => $this->ttl,
					'refresh_ttl'    => $this->ttr,
					'user'           => array(
						'id'         => $user->id,
						'email'      => $user->email,
						'first_name' => $user->first_name,
						'last_name'  => $user->last_name,
						'gender'     => $user->gender
					)
				);
			}
		}

		return null;
	}

	// --------------------------------------------------------------------

	/**
	 * generate_access_token
	 *
	 * Generate a JWT access token for a given user.
	 *
	 * Builds the standard JWT payload including subject (user ID), email,
	 * token type, audience, issuer, issued-at, and expiration claims.
	 * Optionally merges in extra claims provided by the caller.
	 *
	 * @param 	KB_User 	$user 	User object containing at least `id` and `email`.
	 * @param 	array 		$data 	Optional. Additional key-value pairs to merge into the payload.
	 * @param 	int|null 	$ttl 	Optional. Time-to-live in seconds for the token.
	 *
	 * @return string Encoded JWT access token.
	 */
	public function generate_access_token(KB_User $user, array $data = array(), int $ttl = null): string
	{
		// Base payload with standard claims
		$payload = array(
			'sub'   => $user->id,
			'email' => $user->email,
			'type'  => 'access',
			'aud'   => $this->_parent->is_mobile ? 'mobile-app' : 'web-app',
			'iss'   => $this->ci->config->item('domain_url'),
			'iat'   => TIME,
			'exp'   => TIME + ($ttl ?? $this->ttl)
		);

		// Merge any extra payload values, allowing overrides
		empty($data) OR $payload = array_merge($payload, $data);

		// Encode and return the JWT token
		return $this->encode($payload, $ttl);
	}

	// --------------------------------------------------------------------

	/**
	 * generate_refresh_token
	 *
	 * Generates a new cryptographically secure refresh token.
	 *
	 * This method produces an 80-character hexadecimal string using 40 bytes
	 * of secure random data. Can be reused wherever refresh tokens are needed.
	 *
	 * @param 	int 	$bytes 	Number of bytes to generate.
	 *
	 * @return 	string
	 */
	public function generate_refresh_token(int $bytes = 40): string
	{
		return bin2hex(random_bytes($bytes));
	}

	// --------------------------------------------------------------------

	/**
	 * Deletes expired access tokens from the database.
	 *
	 * @uses 	Kbcore_purge::tokens() 	To purge expired JWT tokens.
	 *
	 * @return 	int 	Number of tokens purged.
	 */
	public function purge(): int
	{
		return $this->_parent->purge->tokens();
	}

	// --------------------------------------------------------------------
	// Private Methods
	// --------------------------------------------------------------------

	/**
	 * Base64 URL encodes a string (RFC 7515).
	 *
	 * @param 	string 	$data 	Input string.
	 *
	 * @return 	string 	Encoded string.
	 */
	private function base64_url_encode($data)
	{
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	// --------------------------------------------------------------------

	/**
	 * Base64 URL decodes a string (RFC 7515).
	 *
	 * @param 	string 	$data 	Encoded string.
	 *
	 * @return 	string 	Decoded string.
	 */
	private function base64_url_decode($data)
	{
		$pad = strlen($data) % 4;

		($pad > 0) && $data .= str_repeat('=', 4 - $pad);

		return base64_decode(strtr($data, '-_', '+/'));
	}

}
