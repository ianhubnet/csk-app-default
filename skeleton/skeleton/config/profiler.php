<?php
defined('BASEPATH') OR die;

$config['benchmarks']         = true; // Elapsed time of Benchmark points and total execution time
$config['config']             = true; // CodeIgniter Config variables
$config['controller_info']    = true; // The Controller class and method requested
$config['get']                = true; // Any GET data passed in the request
$config['http_headers']       = true; // The HTTP headers for the current request
$config['languages']          = true; // Loaded language files.
$config['memory_usage']       = true; // Amount of memory consumed by the current request, in bytes
$config['post']               = true; // Any POST data passed in the request
$config['queries']            = true; // Listing of all database queries executed, including execution time
$config['registry']           = true; // Object Cache/Registry.
$config['session_data']       = true; // Data stored in the current session
$config['uri_string']         = true; // The URI of the current request
$config['query_toggle_count'] = 25; // The number of queries after which the query block will default to hidden.
