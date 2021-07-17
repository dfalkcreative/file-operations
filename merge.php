<?php
/**
 * This script will merge all text files from a target directory into a single HTML
 * file which can be used to then print, display on the web, etc.
 *
 * @author Daniel Falk
 */

require_once('helpers.php');

$input = '/example-input';
$output = "$input/output.html";

// Write the merged file.
$file = fopen($output, "w");
fwrite($file, merge($input));
fclose($file);

exit();