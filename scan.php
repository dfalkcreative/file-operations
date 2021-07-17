<?php
/**
 * This script recursively navigates a root directory to find a specific extension. After
 * traversing is completed, all discovered files will be moved to a target directory for
 * future modification.
 *
 * @author Daniel Falk
 */

require_once('helpers.php');

$input = '/example-input';
$output = '/example-output';

// Find all files and move them to the appropriate directory.
$files = [];
read($input, 'txt', $files);
move($files, $output);

exit();