<?php
/*
 * Traq
 * Copyright (C) 2009-2012 Jack Polgar
 * 
 * This file is part of Traq.
 * 
 * Traq is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 only.
 * 
 * Traq is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Traq. If not, see <http://www.gnu.org/licenses/>.
 */

// Set the content type and charset to text/css and UTF-8.
header("content-type: text/css; charset: UTF-8;");

// Check if we can gzip the page or not/
if (extension_loaded('zlib'))
{
	// We can!
	ob_start('ob_gzhandler');
}

// Check for the CSS index in the request array..
if (!isset($_REQUEST['css']))
{
	exit;
}

// Fetch the request class.
require "./system/avalon/libs/request.php";

$output = array();
foreach (explode(',', $_REQUEST['css']) as $file)
{
	// Check if the file exists...
	if (file_exists(__DIR__ . "/assets/css/{$file}.css"))
	{
		// Replace the :baseurl: placeholder with the base URL
		// and send it to the output array.
		$output[] = str_replace(':baseurl:', Request::base(), file_get_contents(__DIR__ . "/assets/css/{$file}.css"));
	}
}

// Remove comments and such from the output.
$output = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $output);

// Minify the CSS.
echo str_replace(array("\n\r", "\n", "\r", "\t", '  ', '   ', '    '), '', implode('', $output));