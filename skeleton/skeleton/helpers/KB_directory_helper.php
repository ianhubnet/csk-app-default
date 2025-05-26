<?php
defined('BASEPATH') OR die;

/**
 * KB_directory_helper
 *
 * Extends CodeIgniter directory helper.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Helpers
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.33
 * @version 	2.1
 */

// --------------------------------------------------------------------

if ( ! function_exists('directory_empty'))
{
	/**
	 * Checks whether the given directoty is empty.
	 *
	 * @param 	string 	$path 	The directory path to check.
	 * @param 	string 	$file_to_ignore 	Delete even this file is found.
	 * @return 	bool
	 */
	function directory_is_empty($path, $file_to_ignore = null)
	{
		if (is_dir($path) && $handle = opendir($path))
		{
			while (false !== ($entry = readdir($handle)))
			{
				if ($entry != '.' && $entry != '..' && (empty($file_to_ignore) OR $entry != $file_to_ignore))
				{
					closedir($handle);
					return false;
				}
			}

			closedir($handle);
			return true;
		}

		return false;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('directory_delete'))
{
	/**
	 * Recursively delete a directory and its contents.
	 *
	 * Handles symlinks (files or directories), normal files,
	 * and nested subdirectories. Skips version control folders.
	 *
	 * @param 	string 	$dir 	Path to the directory or symlink to delete.
	 * @return 	bool 	True on success, false on failure.
	 */
	function directory_delete($dir)
	{
		// If it's a symlink, remove the link and return.
		if (is_link($dir))
		{
			return @rmdir($dir);
		}

		// Not a directory? Nothing to do.
		elseif ( ! is_dir($dir))
		{
			return false;
		}

		// Get directory contents.
		$elements = scandir($dir);
		$ignored  = array('.', '..', '.git', '.github');

		foreach ($elements as $element)
		{
			if (in_array($element, $ignored, true))
			{
				continue;
			}

			$path = $dir.DIRECTORY_SEPARATOR.$element;

			// If it's a symlink, remove the link only.
			if (is_link($path))
			{
				@is_dir($path) ? @rmdir($path) : @unlink($path);
				continue;
			}

			// If it's a directory, recurse into it.
			if (is_dir($path))
			{
				directory_delete($path);
			}
			// Otherwise, it's a file, so delete it.
			else
			{
				@unlink($path);
			}
		}

		// Finally, remove the now-empty directory.
		return @rmdir($dir);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('directory_files'))
{
	/**
	 * Returns a list of all files in the selected directory and all its 
	 * subdirectories up to 100 levels deep.
	 *
	 * @since 	1.34
	 *
	 * @param 	string 	$path 	The full path to the directory.
	 * @param 	int 	$levels 	How deeper we shall go.
	 * @param 	array 	$exclude 	Array of folders/files to skip.
	 * @return 	mixed 	Array of files if found, else false.
	 */
	function directory_files($path = '', $levels = 100, $exclude = array())
	{
		// Nothing to do if no path or levels provided.
		if (empty($path) OR ! $levels)
		{
			return false;
		}

		// We format path and prepare an empty files array.
		$path  = rtrim($path, '/\\').DS;
		$files = array();

		// We open the directory and make sure it's valid.
		$dir = @opendir($path);
		if (false !== $dir)
		{
			while (false !== ($file = readdir($dir)))
			{
				/**
				 * We make sure to skip current and parent folders links, as well
				 * as hidden and excluded files.
				 */
				if (in_array($file, array('.', '..'), true) 
					OR ('.' === $file[0] OR in_array($file, $exclude, true)))
				{
					continue;
				}

				// In case of a directory, we list its files.
				if (is_dir($path.$file))
				{
					$files2 = directory_files($path.$file, $levels - 1);
					if ( ! empty($files2))
					{
						$files = array_merge($files, $files2);
					}
					else
					{
						$files[] = $path.$file.DS;
					}
				}
				// Is is a file?
				else
				{
					$files[] = $path.$file;
				}
			}
		}

		// We close the directory and return files.
		@closedir($dir);
		return $files;
	}
}
