<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_purge Driver
 *
 * This driver is responsible for all automated and scheduled
 * purging operations within the CSK framework.
 * It handles cleanup of:
 * 	- Expired or orphaned records across database tables.
 *  - Stale sessions from multiple session drivers.
 *  - Inactive online users.
 *
 * It is designed with careful consideration to:
 * - Maintain database integrity.
 * - Respect foreign key-like relationships (even in non-FK MyISAM setups).
 * - Minimize performance impact by spacing out purges via scheduled intervals.
 *
 * Core Philosophy:
 * Purging is not just cleaning; it's **preventive maintenance** that keeps
 * your app running smoothly and your DB lean.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2025, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.135
 */
class Kbcore_purge extends KB_Driver
{
	/**
	 * Frequency of database purge execution.
	 *
	 * This determines how often we run a full DB cleanup
	 * (e.g., remove orphaned entities and stale metadata).
	 *
	 * @var int
	 */
	protected $db_purge_int = MONTH_IN_SECONDS;

	/**
	 * Frequency for purging primary keys (optional and app-specific).
	 *
	 * Intended for periodic re-indexing or resetting auto-increment
	 * values when necessary to optimize insert speed and reduce gaps.
	 *
	 * @var int
	 */
	protected $pk_purge_int = MONTH_IN_SECONDS * 2;

	/**
	 * Frequency of online user tracking cleanup.
	 *
	 * Determines how often we check for users that have been
	 * "stuck" online for too long (usually from failed disconnections).
	 *
	 * @var int
	 */
	protected $auth_purge_int = MINUTE_IN_SECONDS * 15;

	/**
	 * JWT token purge interval in seconds.
	 *
	 * This determines how frequently the `tokens()` purge method
	 * is allowed to run. The value is set to 6 hours (in seconds),
	 * meaning expired or revoked tokens will be cleaned up at most
	 * once every 6 hours, controlled by the `next_jwt_purge` option.
	 *
	 * @var int
	 */
	protected $jwt_purge_int = HOUR_IN_SECONDS * 6;

	/**
	 * Maximum age (in seconds) an activity log can be retained before purge.
	 *
	 * This defines how long activity entries should be kept in the database
	 * before there are eligible for archival and deletion during routing
	 * maintenance. Default to 6 months (MONTH_IN_SECONDS * 6).
	 *
	 * @var int
	 */
	protected $activities_lifespan = MONTH_IN_SECONDS * 6;

	// --------------------------------------------------------------------

	/**
	 * Run all purge routines.
	 *
	 * This is the master entry point. When called, it:
	 * 	- Logs the purge start.
	 * 	- Runs each purge method (DB, online users, sessions).
	 * 	- Logs the number of total entries purged.
	 *
	 * Designed to be triggered via a CRON job, admin panel,
	 * or scheduled CLI command.
	 *
	 * @return 	int 	Total number of affected (purged) entries.
	 */
	public function run()
	{
		$count = 0;
		$duration = microtime(true);

		if (is_cli())
		{
			log_message('debug', '[CRON] Site Purge: Initialized...');
			echo sprintf('%s - [CRON] Site Purge: Initialized...', date('Y-m-d H:i', TIME)).PHP_EOL;
		}
		else
		{
			log_message('debug', '[WEB] Site Purge: Initialized...');
		}

		$count += $this->database(); 				// Orphan DB cleanup
		$count += $this->mark_offline();			// Stale online user cleanup
		$count += $this->sessions();				// Expired session cleanup
		$count += $this->_parent->backups->purge(); // Old backup files
		$count += $this->tokens(); 					// Expired JWT tokens

		/** @todo Fix files deletion, it is not really decent */
		// $count += $this->_parent->files->purge();	// Files that aren't been used

		$duration = number_format(microtime(true) - $duration);

		if (is_cli())
		{
			log_message('debug', "[CRON] Site Purge: Complete in {$duration}s - {$count} entries affected.");
			echo sprintf('%s - [CRON] Site Purge: Complete in %ds- %d entries affected.', date('Y-m-d H:i', TIME), $duration, $count).PHP_EOL;
		}
		else
		{
			log_message('debug', "[WEB] Site Purge: Complete in {$duration}s - {$count} entries affected.");
		}


		return $count;
	}

	// --------------------------------------------------------------------

	/**
	 * database
	 *
	 * This method handles a cascade of DB cleanup steps including:
	 * - Deleting orphaned entities of types: users, groups, and objects.
	 * - Purging broken foreign references (`owner_id`, `parent_id`).
	 * - Removing dangling rows from supporting tables (activities, metadata, etc).
	 *
	 * Think of it as a "mini garbage collector" that respects logical relationships.
	 *
	 * Safety Measures:
	 * - Executes only if the scheduled interval is due.
	 * - Requires a database backup BEFORE proceeding.
	 * - Skips if backup fails or if scheduling fails.
	 *
	 * The cleanup order is carefully chosen to respect table relations:
	 * entities first, followed by users, groups and objects - then the rest.
	 *
	 * @uses 	Kbcore_backups::create() 	For database backup prior to purge.
	 * @uses 	Kbcore_options::set_item()	To update the next scheduled purge time.
	 * @uses 	self::entity_orphans() 		To purge deleted entities of given types.
	 * @uses 	self::entity_references() 	To delete entity broken references.
	 * @uses 	self::table_orphans()		To remove orphaned records.
	 * @uses 	self::primary_keys() 		To reset tables AUTO_INCREMENT.
	 *
	 * @return 	int 	Total records purged across all cleanup tasks.
	 */
	public function database()
	{
		/**
		 * Three (3) conditions are required for this database purge
		 * to be executed:
		 * 	1. The time to purge has come!
		 * 	2. A database backup was successfully created!
		 * 	3. The `next_db_purge` option was updated.
		 */
		if (TIME < $this->ci->config->item('next_db_purge', null, 0)
			OR ! $this->_parent->backups->create('zip', array('locked' => true))
			OR ! $this->_parent->options->set_item('next_db_purge', TIME + $this->db_purge_int))
		{
			return 0;
		}

		// Initialize purge counter.
		$count = 0;

		// Step 1: Purge deleted entities first (must come before content rows).
		$count += $this->entity_orphans('user', 'users');
		$count += $this->entity_orphans('group', 'groups');
		$count += $this->entity_orphans('object', 'objects');

		// Step 1.5: Remove entity relationships that are invalid.
		$count += $this->entity_references();

		// Step 2: Purge actual content rows orphaned from entities.
		$count += $this->table_orphans('users');
		$count += $this->table_orphans('groups');
		$count += $this->table_orphans('objects');

		/**
		 * Step 3: Cleanup secondary tables that reference GUIDs.
		 *
		 * After purging entities, users, objects and other records,
		 * there may still be dangling (orphaned) rows left behind - rows
		 * that reference no longer existing entries (e.g., a metadata row
		 * referencing a deleted user or object).
		 *
		 * This step loops through several key tables and removes rows where
		 * the `guid` no longer exists in the `entities` table, which act as the
		 * authoritative source for all valid entity references in the system.
		 *
		 * This not only keeps the database clean, but also prevents future
		 * issues caused by invalid foreign references.
		 */
		$count += $this->table_orphans('relations', 'guid_from'); 	// Relationships of `guid_from`
		$count += $this->table_orphans('relations', 'guid_to');		// Relationships of `guid_to`
		$count += $this->table_orphans('activities', 'user_id'); 	// User activity logs
		$count += $this->table_orphans('metadata');					// Additional entities metadata
		$count += $this->table_orphans('tokens');					// JWT tokens
		$count += $this->table_orphans('translations');				// Translatable content
		$count += $this->table_orphans('variables');				// Temporary or variable storage

		/**
		 * Step 4: Cleanup and archive activities table.
		 *
		 * This operation is schedule by default to happens once every
		 * `activities_lifespan`, so it should only be execute once per
		 * that period.
		 *
		 * Before deletion, all activity logs are put into a single file
		 * then archives and GZipped. Once that's done, rows are deleted
		 * from the database safely.
		 */
		$count += $this->activities();

		/**
		 * If the command was executed via CRON (CLI) we make sure
		 * to print the message so we can track CRON logs.
		 */
		if ($count > 0 && is_cli())
		{
			log_message('debug', "[CRON] Database Purge: Complete - {$count} entries deleted.");
			echo sprintf('%s - [CRON] Database Purge: Complete - %d entries deleted.', date('Y-m-d H:i', TIME), $count).PHP_EOL;
		}
		elseif ($count > 0)
		{
			log_message('debug', "[WEB] Database Purge: Complete - {$count} entries deleted.");
		}

		/**
		 * Step 4: Reset some tables AUTO_INCREMENT
		 * This should be executed only if any records were deleted.
		 */
		$this->primary_keys();

		// Return total number of deleted records across all purge steps.
		return $count;
	}

	// --------------------------------------------------------------------

	/**
	 * mark_offline
	 *
	 * Mark users offline after being stuck "online" for too long.
	 *
	 * Some users remain marked online due to:
	 * - Session crashes
	 * - Improper logouts
	 * - Network disconnects
	 *
	 * This method resets "online" flags for any user who has
	 * been stuck for more than 4 hours, assuming they have timed out.
	 *
	 * @return 	int 	Number of users updated.
	 */
	public function mark_offline()
	{
		/**
		 * For this to be executed:
		 * 	1. Time to mark offline has come.
		 * 	2. The `next_online_purge` was successfully updated.
		 */
		if (TIME < $this->ci->config->item('next_online_purge', null, 0)
			OR ! $this->_parent->options->set_item('next_online_purge', TIME + $this->auth_purge_int))
		{
			return 0;
		}

		/**
		 * The query hear affects users that have `check_online_at` greater
		 * than `0`, which means they logged in to the site at least one,
		 * if these users last `check_online_at` is older than four (4) hours
		 * we make sure to reset their `online` status to `0` as well as the
		 * value of `check_online_at`.
		 */
		$this->ci->db
			->set('online', 0)
			->set('check_online_at', 0)
			->where('check_online_at >', 0)
			->where('check_online_at <=', TIME - (HOUR_IN_SECONDS * 4)) // 4 hours (?))
			->update('users');

		if (($count = $this->ci->db->affected_rows()) <= 0)
		{
			return 0;
		}
		elseif (is_cli())
		{
			log_message('debug', "[CRON] Online Purge: {$count} users affected.");
			echo sprintf('%s - [CRON] Online Purge: %d users affected.', date('Y-m-d H:i', TIME), $count).PHP_EOL;
		}
		else
		{
			log_message('debug', "[WEB] Online Purge: {$count} users affected.");
		}

		return $count;
	}

	// --------------------------------------------------------------------

	/**
	 * sessions
	 *
	 * Clean up expired sessions based on the current session driver.
	 *
	 * This method handles session expiration cleanup for all supported
	 * session drivers configured in CodeIgniter 3:
	 *
	 * - 'files': Scans the session save path directory and deletes session
	 *   files older than the expiration time.
	 *
	 * - 'database': Deletes session records from the database table
	 *   (sess_save_path is treated as the table name) that have expired
	 *   based on timestamp.
	 *
	 * - 'redis': Connects to the Redis server (sess_save_path contains connection info),
	 *   then uses SCAN to iterate over session keys and deletes keys that
	 *   have expired(TTL = -2 or 0). Uses non-blocking SCAN to avoid performance hits.
	 *
	 * - 'memcached': Memcached does not support key iteration or selective
	 *   deletion. Expired sessions are automatically removed based on TTL.
	 *   Explicit purge is not implemented here and generally not needed.
	 *
	 * Notes:
	 * - The method reads configuration from CodeIgniter's session config:
	 *   'sess_driver', 'sess_save_path', 'sess_expiration', 'sess_cookie_name'.
	 * - The TIME constant is defined as the current timestamp for expiry calculation.
	 * - For Redis, 'sess_save_path' should be a valid Redis connection
	 *   string (e.g., tcp://127.0.0.1:6379).
	 * - For database, 'sess_save_path' is expected to be the session table name.
	 *
	 * Why this matters:
	 * - Prevents storage bloat.
	 * - Improves session lookup performance.
	 * - Ensures old sessions don't linger around.
	 *
	 * @return 	int 	The number of expired sessions successfully purged/deleted.
	 */
	public function sessions()
	{
		// Session purge already happened? Skip...
		if (TIME < $this->ci->config->item('next_sess_purge', null, 0)
			OR ! $this->_parent->options->set_item('next_sess_purge', TIME + $this->ci->config->item('sess_expiration')))
		{
			return 0;
		}

		// Let's start...
		$count       = 0;
		$sess_driver = $this->ci->config->item('sess_driver');
		$sess_path   = $this->ci->config->item('sess_save_path');
		$sess_expiry = TIME - $this->ci->config->item('sess_expiration', null, 7200);

		// 'files' driver?
		if ($sess_driver === 'files'
			&& is_dir($sess_path)
			&& $files = glob($sess_path.'/'.$this->ci->config->item('sess_cookie_name').'*'))
		{
			foreach ($files as $file)
			{
				if (is_file($file) && filemtime($file) < $sess_expiry)
				{
					@unlink($file);
					$count++;
				}
			}
		}
		// 'database' driver?
		elseif ($sess_driver === 'database')
		{
			$this->ci->db->where('timestamp <', $sess_expiry)->delete($sess_path);
			$count += $this->ci->db->affected_rows();
		}
		// 'redis' driver?
		elseif ($sess_driver === 'redis' && class_exists('Redis', false))
		{
			$redis = new Redis();

			// Parse TCP.
			$parts = parse_url($sess_path);
			$host = $parts['host'] ?? '127.0.0.1';
			$port = $parts['port'] ?? 6379;

			try {
				$redis->connect($host, $port);

				$iterator    = null;
				$sess_cookie = $this->ci->config->item('sess_cookie_name', null, 'ci_session');

				do {
					if (($keys = $redis->scan($iterator, $sess_cookie.':*')) !== false)
					{
						foreach ($keys as $key)
						{
							$ttl = $redis->ttl($key);
							if ($ttl === -2 OR $ttl === 0)
							{
								$redis->del($key);
								$count++;
							}
						}
					}
				} while ($iterator > 0);

			} catch (Exception $e) {
				log_message('error', 'Redis session purge failed: '.$e->getMessage());
			}
		}
		// 'memcached' driver?
		elseif ($sess_driver === 'memcached' && class_exists('Memcached', false))
		{
			log_message('error', "Session purge not implemented for driver '{$sess_driver}'.");
		}

		if ($count <= 0)
		{
			return 0;
		}
		elseif (is_cli())
		{
			log_message('debug', "[CRON] Session Purge: {$count} sessions deleted.");
			echo sprintf('%s - [CRON] Session Purge : %d sessions deleted.', date('Y-m-d H:i', TIME), $count).PHP_EOL;
		}
		else
		{
			log_message('debug', "[WEB] Session Purge: {$count} sessions deleted.");
		}

		return $count;
	}

	// --------------------------------------------------------------------

	/**
	 * online_tokens
	 *
	 * Delete any active online session tokens and purges outdated one
	 * from the `variables` table.
	 *
	 * This method removes records used to track a user's online status.
	 * If a specific user ID is provided, it will also remove remove the
	 * user's token in addition to expired ones.
	 *
	 * @param 	int 	$user_id 	Optional user ID.
	 * @return 	bool 	True if any rows were deleted, False otherwise.
	 */
	public function online_tokens($user_id = 0)
	{
		// Delete all expired online tokens.
		$this->ci->db
			->where('name', $this->_parent->auth->online_token_var_name)
			->where('updated_at <=', TIME);

		// Also delete the token for a specific user, if provided.
		if (is_numeric($user_id) && 0 < $user_id = (int) $user_id)
		{
			$this->ci->db
				->or_where('guid', $user_id)
				->where('name', $this->_parent->auth->online_token_var_name);
		}

		$this->ci->db->delete('variables');

		return (0 < $this->ci->db->affected_rows());
	}

	// --------------------------------------------------------------------

	/**
	 * password_codes
	 *
	 * Deletes expired password reset codes and optionally clears the code
	 * for a specific user.
	 *
	 * This is used to prevent old or used reset codes from lingering in
	 * the database.
	 * Only entries under the pass code variable name are affected.
	 *
	 * @param 	int 	$user_id 	Optional user ID.
	 * @return 	bool 	True if any rows were deleted, False otherwise.
	 */
	public function password_codes($user_id = 0)
	{
		// Delete all expired password codes.
		$this->ci->db
			->where('name', $this->_parent->auth->password_code_var_name)
			->where('updated_at <=', TIME);

		// Also delete the code for a specific user, if provided.
		if (is_numeric($user_id) && 0 < $user_id = (int) $user_id)
		{
			$this->ci->db
				->or_where('guid', $user_id)
				->where('name', $this->_parent->auth->password_code_var_name);
		}

		$this->ci->db->delete('variables');

		return (0 < $this->ci->db->affected_rows());
	}

	// --------------------------------------------------------------------

	/**
	 * activation_codes
	 *
	 * Removes expired or used account activation codes from database.
	 *
	 * Primarily called after a user activates their account or during
	 * cleanup routines.
	 * Accepts a user ID to also delete that specific users's code if exists.
	 *
	 * @param 	int 	$user_id 	Optional user ID.
	 * @return 	bool 	True if any records were deleted, False otherwise.
	 */
	public function activation_codes($user_id)
	{
		// Delete all expired activation codes.
		$this->ci->db
			->where('name', $this->_parent->auth->activation_code_var_name)
			->where('updated_at <=', TIME);

		// Also delete the code for a specific user, if provided.
		if (is_numeric($user_id) && 0 < $user_id = (int) $user_id)
		{
			$this->ci->db
				->or_where('guid', $user_id)
				->where('name', $this->_parent->auth->activation_code_var_name);
		}

		$this->ci->db->delete('variables');

		return (0 < $this->ci->db->affected_rows());
	}

	// --------------------------------------------------------------------

	/**
	 * auth_codes
	 *
	 * Deletes expired or used two-factor authentication codes for a user.
	 *
	 * Typically used after successful verification or to clear stale codes.
	 * Accepts a user ID to ensure only that user's code is targeted as well
	 * as old entries.
	 *
	 * @param 	int 	$user_id 	Optional user ID.
	 * @return 	bool 	True if any rows were deleted, False otherwise.
	 */
	public function auth_codes($user_id)
	{
		// Delete all expired two-factor codes.
		$this->ci->db
			->where('name', $this->_parent->auth->tfa_code_var_name)
			->where('updated_at <=', TIME);

		// Also delete two-factor codes for a specific user, if provided.
		if (is_numeric($user_id) && 0 < $user_id = (int) $user_id)
		{
			$this->ci->db
				->or_where('guid', $user_id)
				->where('name', $this->_parent->auth->tfa_code_var_name);
		}

		$this->ci->db->delete('variables');

		return (0 < $this->ci->db->affected_rows());
	}

	// --------------------------------------------------------------------

	/**
	 * quick_login
	 *
	 * Deletes expired or used one-click login codes.
	 *
	 * One-click login (or magic link) codes are single-use. This method removes
	 * them after usage or clears expired ones to prevent misuse or DB clutter.
	 *
	 * @param 	int 	$user_id 	Optional user ID.
	 * @return 	bool 	True if any rows were deleted, False otherwise.
	 */
	public function quick_login($user_id)
	{
		// Delete all expired one-click codes.
		$this->ci->db
			->where('name', $this->_parent->auth->quick_login_var_name)
			->where('updated_at <=', TIME);

		// Also delete one-click codes for a specific user, if provided.
		if (is_numeric($user_id) && 0 < $user_id = (int) $user_id)
		{
			$this->ci->db
				->or_where('guid', $user_id)
				->where('name', $this->_parent->auth->quick_login_var_name);
		}

		$this->ci->db->delete('variables');

		return (0 < $this->ci->db->affected_rows());
	}

	// --------------------------------------------------------------------

	/**
	 * email_codes
	 *
	 * Deletes user's email change verification code form the database.
	 * Also purges expired codes that are older than 2 days, regardless
	 * of user.
	 *
	 * This method target records in the `variables` table with the name
	 * `email_code`. It first removes all entries that haven't been updated
	 * in the last 48 hours (considered expired). If a valid `$user_id` is
	 * provided, it will also delete that user's specific email change code
	 * regardless of its age.
	 *
	 * @param 	int 	$user_id 	Optional user ID.
	 * @return 	bool 	True if any rows were deleted, False otherwise.
	 */
	public function email_codes($user_id = 0)
	{
		// Remove general expired email change codes older than 2 days
		$this->ci->db
			->where('name', $this->_parent->users->email_code_var_name)
			->where('updated_at <=', TIME - (DAY_IN_SECONDS * 2));

		// Optionally delete a specific user's email code regardless of age.
		if (is_numeric($user_id) && 0 < $user_id = (int) $user_id)
		{
			$this->ci->db
				->or_where('guid', $user_id)
				->where('name', $this->_parent->users->email_code_var_name);
		}

		$this->ci->db->delete('variables');

		return (0 < $this->ci->db->affected_rows());
	}

	// --------------------------------------------------------------------
	// Private Methods
	// --------------------------------------------------------------------

	/**
	 * primary_keys
	 *
	 * Attempts to reset AUTO_INCREMENT values for certain tables.
	 *
	 * This method is designed to clean up and "defragment" the primary
	 * keys of specific tables by resetting their AUTH_INCREMENT to 1.
	 *
	 * This is only performed if:
	 * 	1. The scheduled time to purge primary keys has come.
	 * 	2. The `next_pk_purge` option is successfully updated.
	 *
	 * Tables affected:
	 * 	- activities
	 * 	- metadata
	 * 	- relations
	 * 	- variables
	 *
	 * Note:
	 * 	- This will only be effective if the tables are empty or all IDS are removed.
	 * 	- It's a cosmetic and optional cleanup - doesn't affect app logic.
	 * 	- Should be safe and low-cost in run occasionally.
	 *
	 * @return 	void
	 */
	private function primary_keys()
	{
		// Check if the purge interval has passed
		// and update the next schedule purge time.
		if (TIME < $this->ci->config->item('next_pk_purge', null, 0)
			OR ! $this->_parent->options->set_item('next_pk_purge', TIME + $this->pk_purge_int))
		{
			return; // Exit early!
		}

		// Reset AUTO_INCREMENT on each of the specified tables to 1.
		// This is safe only if the tables are empty or IDs can be reused.
		$this->ci->db->query('ALTER TABLE `'.$this->ci->db->dbprefix('activities').'` AUTO_INCREMENT = 1;');
		$this->ci->db->query('ALTER TABLE `'.$this->ci->db->dbprefix('entities').'` AUTO_INCREMENT = 1;');
		$this->ci->db->query('ALTER TABLE `'.$this->ci->db->dbprefix('metadata').'` AUTO_INCREMENT = 1;');
		$this->ci->db->query('ALTER TABLE `'.$this->ci->db->dbprefix('relations').'` AUTO_INCREMENT = 1;');
		$this->ci->db->query('ALTER TABLE `'.$this->ci->db->dbprefix('variables').'` AUTO_INCREMENT = 1;');

		// Was this executed via CLI/CRON?
		if (is_cli())
		{
			log_message('debug', '[CRON] Primary Keys: Complete');
			echo sprintf('%s - [CRON] Primary Keys: Complete', date('Y-m-d H:i', TIME)).PHP_EOL;
		}
		else
		{
			log_message('debug', '[WEB] Primary Keys: Complete');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * entity_orphans
	 *
	 * Deletes orphaned records from the `entities` table for a specific entity type.
	 *
	 * This method removes all rows from `entities` where:
	 *   - The `type` matches the provided `$type` (e.g., 'user', 'object'), AND
	 *   - The corresponding record no longer exists in the related `$table`.
	 *
	 * For example: If a user is deleted from the `users` table but their entity
	 * remains in `entities`, this method will detect and delete that orphan.
	 *
	 * @param 	string 	$type 	The type of entity to purge (e.g., 'user', 'object').
	 * @param 	string 	$table 	The related table to verify against (e.g., 'users').
	 *
	 * @return 	int 	The number of orphaned `entities` rows deleted.
	 *
	 * @note 	The method uses raw SQL for performance, and protects against
	 *        	SQL injection by escaping the entity type string.
	 */
	private function entity_orphans(string $type, string $table)
	{
		// Use a static variable to avoid recreating the SQL string on each call.
		static $sql;

		// Bail out early if the table name is empty or doesn't exist in the DB.
		if (empty($table) OR ! $this->ci->db->table_exists($table))
		{
			return 0;
		}

		// Build the SQL template only once, using NOT IN to detect orphans.
		if (empty($sql))
		{
			$sql = <<<SQL
DELETE FROM `entities`
WHERE `type` = '%2\$s'
AND `id` NOT IN (SELECT guid FROM `%1\$s`)
SQL;
		}

		// Prefix the table name using CodeIgniter’s DB prefixing system.
		$prefixed_table = $this->ci->db->dbprefix($table);

		// Escape the entity type to prevent SQL injection.
		$escaped_type = $this->ci->db->escape_str($type);

		// Execute the final SQL with the table and type placeholders replaced.
		$this->ci->db->query(sprintf($sql, $prefixed_table, $escaped_type));

		// Calculate affected rows.
		if (($count = $this->ci->db->affected_rows()) <= 0)
		{
			return 0;
		}
		elseif (is_cli())
		{
			log_message('debug', "[CRON] Entity `{$prefixed_table}` Orphans: Complete - {$count} orphans deleted.");
			echo sprintf('%s - [CRON] Entity `%s` Orphans: Complete - %d orphans deleted.', date('Y-m-d H:i', TIME), $prefixed_table, $count).PHP_EOL;
		}
		else
		{
			log_message('debug', "[WEB] Entity `{$prefixed_table}` Orphans: Complete - {$count} orphans deleted.");
		}

		// Return the total number of deleted rows.
		return $count;
	}

	// --------------------------------------------------------------------

	/**
	 * entity_references
	 *
	 * Scans all entities for broken references in `parent_id` and `owner_id`.
	 *
	 * If any of those fields point to a non-existent entity ID in the same table,
	 * they are reset to `0`. This avoids invalid foreign key references and ensures
	 * referential consistency across all `entities`.
	 *
	 * This method replaces the idea of doing it via a recursive or fragile SQL trigger.
	 * It is safe to run periodically or after bulk deletions.
	 *
	 * @return 	int 	Total number of entity records updated.
	 */
	private function entity_references()
	{
		$count = 0;

		// Prefix the `entities` table name.
		$entities = $this->ci->db->dbprefix('entities');

		// Reusable SQL fragment to clean a specific reference
		// (e.g., parent_id or owner_id).
		$sql = <<<SQL
UPDATE `{$entities}` e
LEFT JOIN `{$entities}` p ON p.id = e.%1\$s
SET e.%1\$s = 0
WHERE e.%1\$s IS NOT NULL AND e.%1\$s > 0 AND p.id IS NULL
SQL;

		// Clean broken `parent_id` references.
		$this->ci->db->query(sprintf($sql, 'parent_id'));
		$count += $this->ci->db->affected_rows();

		// Clean broken `owner_id` references.
		$this->ci->db->query(sprintf($sql, 'owner_id'));
		$count += $this->ci->db->affected_rows();

		// Log if anything was updated.
		if ($count <= 0)
		{
			return 0;
		}
		elseif (is_cli())
		{
			log_message('debug', "[CRON] Entity References: Complete - {$count} entries updated.");
			echo sprintf('%s - [CRON] Entity References: Complete - %d entries updated.', date('Y-m-d H:i', TIME), $count).PHP_EOL;
		}
		else
		{
			log_message('debug', "[WEB] Entity References: Complete - {$count} entries updated.");
		}

		return $count;
	}

	// --------------------------------------------------------------------

	/**
	 * table_orphans
	 *
	 * Deletes orphaned records from a specified table by checking the `guid`
	 * column against the master `entities` table.
	 *
	 * This generic method is used to clean any table that stores rows tied to
	 * entities (e.g., users, objects, metadata, activities, etc.), where each
	 * row includes a `guid` (foreign key reference to `entities.guid`).
	 *
	 * The idea is to remove any row in the given table where `guid` does NOT
	 * exist in the `entities` table anymore — i.e., that row has become orphaned.
	 *
	 * For example:
	 * - A user is deleted, but is still referenced in the activities table.
	 * - An object is removed, but its associated metadata remains.
	 *
	 * Implementation:
	 * - Constructs a DELETE query with a NOT IN clause.
	 * - Table name is passed as a string argument.
	 * - Only works if the table includes a `guid` column.
	 *
	 * @param 	string 	$table 			Table name to purge.
	 * @param 	string 	$foreign_key 	The table's key to use (default: `guid`).
	 * @return 	int 	Number of purged rows from the table.
	 */
	public function table_orphans(string $table, string $foreign_key = 'guid')
	{
		static $sql;

		// Bail out early if the table name is empty or doesn't exist in the DB.
		if (empty($table) OR ! $this->ci->db->table_exists($table))
		{
			return 0;
		}

		// Reusable SQL fragment.
		if (empty($sql))
		{
			$entities = $this->ci->db->dbprefix('entities');
			$sql = "DELETE FROM `%1\$s` WHERE NOT EXISTS (SELECT `id` FROM `{$entities}` WHERE `id`=`%2\$s`)";
		}

		// Prefix the table name using CodeIgniter’s DB prefixing system.
		$prefixed_table = $this->ci->db->dbprefix($table);

		// Execute the final SQL with the table and type placeholders replaced.
		$this->ci->db->query(sprintf($sql, $prefixed_table, $foreign_key));

		if (($count = $this->ci->db->affected_rows()) <= 0)
		{
			return 0;
		}
		elseif (is_cli())
		{
			log_message('debug', "[CRON] `{$prefixed_table}` orphans: Complete - {$count} entries deleted.");
			echo sprintf('%s - [CRON] `%s` orphans: Complete - %d entries deleted.', date('Y-m-d H:i', TIME), $prefixed_table, $count).PHP_EOL;
		}
		else
		{
			log_message('debug', "[WEB] `{$prefixed_table}` orphans: Complete - {$count} entries deleted.");
		}

		return $count;
	}

	// --------------------------------------------------------------------

	/**
	 * activities
	 *
	 * Purges old activity logs from the database and archives them to
	 * a gzipped file, if possible.
	 *
	 * This method:
	 * - Checks whether a purge should happen based on `next_log_purge`.
	 * - Collects all `activities` entries older than the defined lifespan.
	 * - Archives them into a gzipped file, appending them to avoid loss.
	 * - Deletes the archived records from the database.
	 * - Updates the `next_log_purge` option to prevent frequent executions.
	 *
	 * @return 	int 	Number of deleted (and archived) entries.
	 */
	public function activities()
	{
		// Abort if it's not yet time for the next purge OR failed to update
		// the next purge time. The second condition ensures no overlap between
		// concurrent CRONs.
		if (TIME < $this->ci->config->item('next_log_purge', null, 0)
			OR ! $this->_parent->options->set_item('next_log_purge', TIME + $this->activities_lifespan))
		{
			return 0;
		}

		// Retrieve all activities older than the threshold timestamp.
		$query = $this->ci->db
			->where('created_at <', TIME - $this->activities_lifespan)
			->get('activities');

		// If no entries matched, there's nothing to archive or delete.
		if (empty($results = $query->result_array()))
		{
			return 0;
		}

		// Determine the archive directory and make sure it exits.
		if ( ! is_dir($archive_dir = APPPATH.'logs/archives/')
			&& ! mkdir($archive_dir, 0755, true) // recursive = true just in case
			&& ! is_dir($archive_dir))
		{
			// Log this critical error so that admin can fix it.
			log_message('critical', 'Failed to create archive directory: '.$archive_dir);

			// Revert `next_log_purge` time updated.
			$this->_parent->options->set_item('next_log_purge', TIME);

			// Bail early if you can't archive
			return 0;
		}

		// If the archive file doesn't exist, initialize it as empty.
		if ( ! is_file($archive_file = $archive_dir.'activity-archive-'.date('Y-m-d').'.log.gz'))
		{
			file_put_contents($archive_file, $this->_gzencode('', 9));
		}

		// Decode the existing archive file's contents (if any).
		$content = $this->_gzdecode(file_get_contents($archive_file)) ?: '';

		// Append new log data as JSON strings line by line.
		foreach ($results as $row)
		{
			$content .= json_encode($row).PHP_EOL;
		}

		// Compress and save the updated archive content.
		file_put_contents($archive_file, $this->_gzencode($content, 9));

		// Delete the now-archived entries from the database.
		$ids = array_column($results, 'id');
		$this->ci->db
			->where_in('id', $ids)
			->delete('activities');

		// Get how many rows were actually deleted.
		$count = $this->ci->db->affected_rows();

		// If something was deleted, log the operation.
		if ($count > 0)
		{
			$message = sprintf('Activities: Complete - %d entries deleted and archived.', $count);

			if (is_cli())
			{
				log_message('debug', '[CRON] '.$message);
				echo date('Y-m-d H:i', TIME).' - [CRON] '.$message . PHP_EOL;
			}
			else
			{
				log_message('debug', '[WEB] '.$message);
			}
		}

		return $count;
	}

	// --------------------------------------------------------------------

	/**
	 * tokens
	 *
	 * Purge expired and revoked JWT token entries from the database.
	 *
	 * This method is responsible for cleaning up the `tokens` table by
	 * deleting records that are no longer valid or useful.
	 *
	 * - Tokens that have been explicitly **revoked** (revoked = 1).
	 * - Tokens for which **both** the access and refresh expiration
	 *   timestamps have passed (i.e., completely unusable).
	 *
	 * The cleanup logic ensures the database remains efficient and
	 * uncluttered by removing obsolete tokens, thus reducing potential
	 * attack surfaces and saving storage space.
	 *
	 * To avoid over-purging on frequent hits, it only runs if the current
	 * time exceeds the `jwt_purge_int` value stored in `options`.
	 * If successful, the method updates the value of the current time
	 * plus the configured purge interval (`jwt_purge_int`).
	 *
	 * After deleting, it logs how many token entries were removed and also
	 * prints a message to the CLI (if the call is from a cron job).
	 *
	 * @return 	int 	The number of tokens deleted from the database.
	 */
	public function tokens()
	{
		if (TIME < $this->ci->config->item('next_jwt_purge', null, 0)
			OR ! $this->_parent->options->set_item('next_jwt_purge', TIME + $this->jwt_purge_int))
		{
			return 0;
		}

		$this->ci->db
			->group_start()
				->where('revoked', 1)
				->or_group_start()
					->where('access_expires_at <', TIME)
					->where('refresh_expires_at <', TIME)
				->group_end()
			->group_end()
			->delete('tokens');

		// Get how many rows were actually deleted.
		// If something was deleted, log the operation.
		if (($count = $this->ci->db->affected_rows()) > 0)
		{
			$message = sprintf('JWT Purge: Complete - %d tokens deleted.', $count);

			if (is_cli())
			{
				log_message('debug', '[CRON] '.$message);
				echo date('Y-m-d H:i', TIME).' - [CRON] '.$message . PHP_EOL;
			}
			else
			{
				log_message('debug', '[WEB] '.$message);
			}
		}

		return $count;
	}

	// --------------------------------------------------------------------
	// Utilities
	// --------------------------------------------------------------------

	/**
	 * _gzencode
	 *
	 * Encodes a string to GZ format if possible, fallback to plain.
	 *
	 * @param 	string 	$data 	Raw string to compress.
	 * @param 	int 	$level 	Compression level (default: 9).
	 * @return 	string
	 */
	private function _gzencode(string $data, int $level = 9): string
	{
		return function_exists('gzencode') ? gzencode($data, $level) : $data;
	}

	// --------------------------------------------------------------------

	/**
	 * _gzdecode
	 *
	 * Decodes a GZ string if possible, fallback to plain.
	 *
	 * @param 	string 	$data 	GZ encoded string or plain fallback.
	 * @return 	string
	 */
	private function _gzdecode(string $data): string
	{
		return function_exists('gzdecode') ? gzdecode($data) : $data;
	}

}
