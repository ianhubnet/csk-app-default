<?php

/**
 * Cron Tasks
 *
 * This file allows you to register scheduled tasks that the system will
 * manage and run at defined intervals. These tasks are loaded by the
 * "Kbcore_tasks" driver and executed based on their recurrence and state.
 *
 * Each task must be defined under the `$config['tasks']` array using a
 * unique alias as the key, and an associative array of options:
 *
 *	$config['tasks']['my_task'] = array(
 *		'callback'   => array('my_class', 'my_method'),
 *		'args'       => array('optional', 'arguments'),
 *		'recurrence' => 3600, // Run every hour
 *		'starts_at'  => '02:00', // Optional. Start at 2:00 AM server time
 *		'limit'      => 10,   // (Optional) Run max 10 times
 *		'meta'       => array('note' => 'Something to store'),
 *	);
 *
 * - callback   : A valid callable or method array. Required.
 * - args       : Optional arguments passed to the callback when invoked.
 * - recurrence : Required. The interval (in seconds) between each execution.
 * - starts_at  : Optional. Server clock time (HH:MM) to delay the first run.
 *               Used only for the initial schedule. Example: '00:00', '23:30'
 * - limit      : Optional. Maximum number of times the task may run.
 * - meta       : Optional. Arbitrary metadata to store with the task.
 *
 * You can override core tasks or register your own inside your app's
 * "config/tasks.php" file. CI Skeleton merges both config files and tasks
 * that already exist in the database will not be overridden.
 *
 * @package    App\Config
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2025, Kader Bouyakoub
 * @since      2.156
 */
