<?php
defined('BASEPATH') OR die;

/**
 * CRUD_interface Interface
 *
 * All our custom libraries that handle CRUD operations will implement
 * this interface to make sure to respect the structure.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Interfaces
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.0
 * @version 	1.0
 */
interface CRUD_interface
{
	/**
	 * Create a new row
	 * @access 	public
	 * @param 	array 	$data 	Array of data to insert.
	 * @return 	the new row ID if found, else false.
	 */
	public function create(array $data = array());

	/**
	 * Retrieve a single row by primary key.
	 * @access 	public
	 * @param 	mixed 	$id 	The primary key value.
	 * @return 	object if found, else null
	 */
	public function get($id);

	/**
	 * Retrieve a single row by arbitrary WHERE clause.
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @return 	object if found, else null.
	 */
	public function get_by($field, $match = null);

	/**
	 * Retrieve multiple rows by arbitrary WHERE clause.
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @param 	int 	$limit 	Limit to use for getting records.
	 * @param 	int 	$offset Database offset.
	 * @return 	array o objects if found, else null.
	 */
	public function get_many($field = null, $match = null, $limit = 0, $offset = 0);

	/**
	 * Retrieve all rows.
	 * @access 	public
	 * @param 	int 	$limit 	Limit to use for getting records.
	 * @param 	int 	$offset Database offset.
	 * @return 	array o objects if found, else null.
	 */
	public function get_all($limit = 0, $offset = 0);

	/**
	 * Update a single row by its primary key.
	 * @access 	public
	 * @param 	mixed 	$id 	The primary key value.
	 * @param 	array 	$data 	Array of data to update.
	 * @return 	boolean
	 */
	public function update($id, array $data = array());

	/**
	 * Update all or multiple rows by arbitrary WHERE clause.
	 * @access 	public
	 * @return 	boolean
	 */
	public function update_by();

	/**
	 * Delete a single row by its primary key.
	 * @access 	public
	 * @param 	mixed 	$id 	The primary key value.
	 * @return 	boolean
	 */
	public function delete($id);

	/**
	 * Delete multiple or all rows by arbitrary WHERE clause.
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @return 	boolean
	 */
	public function delete_by($field = null, $match = null);
}
