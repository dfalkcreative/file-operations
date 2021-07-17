<?php

if (!function_exists('is')) {
    /**
     * Indicates whether or not the file is of the appropriate extension.
     *
     * @param $path
     * @param $extension
     * @return bool
     */
    function is($path, $extension)
    {
        if (!file_exists($path)) {
            return false;
        }

        return strtolower(pathinfo($path)['extension']) === $extension;
    }
}


if (!function_exists('read')) {
    /**
     * Recursively navigates through directories to find all matching files.
     *
     * @param $directory
     * @param $extension
     * @param $files
     */
    function read($directory, $extension, &$files)
    {
        if (!file_exists($directory)) {
            return;
        }

        if (!is_dir($directory)) {
            if (is($directory, $extension)) {
                $files[] = $directory;
            }

            return;
        }

        foreach (scandir($directory) as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $file = "$directory/$file";

            if (is_dir($file)) {
                read($file, $extension, $files);
                continue;
            }

            if (is($file, $extension)) {
                $files[] = $file;
            }
        }
    }
}


if (!function_exists('move')) {
    /**
     * Clones a series of files into the appropriate directory.
     *
     * @param array $files
     * @param string $directory
     */
    function move($files = [], $directory = '')
    {
        if (!$directory) {
            return;
        }

        if (!file_exists($directory)) {
            mkdir($directory);
        }

        foreach ($files as $file) {
            if (!file_exists($file)) {
                continue;
            }

            $base = basename($file);
            copy($file, "$directory/$base");
        }
    }
}


if (!function_exists('csv')) {
    /**
     * Reads a CSV and returns the output as an associative array.
     *
     * @param $file
     * @return array
     */
    function csv($file)
    {
        $csv = [];
        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $csv[] = array_combine($header, $row);
        }

        return $csv;
    }
}


if (!function_exists('merge')) {
    /**
     * Used to merge all text files in a given directory and output the resulting HTML.
     *
     * @param $directory
     * @return string
     */
    function merge($directory)
    {
        // Build the base HTML page.
        $output = "
            <html>
                <head>
                    <style>
                        .page {
                            page-break-after: always;
                        }
                        
                        body {
                            font-family: 'Courier New', monospace;
                            font-size: 10px;
                        }
                    </style>
                </head>
            <body>
        ";

        // Join each text file.
        foreach (scandir($directory) as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $file = "$directory/$file";

            if (!is_dir($file) && is($file, 'txt')) {
                $handle = fopen($file, "r");

                // Verify that the file was opened successfully.
                if (!$handle) {
                    continue;
                }

                $output .= "<div class='page'>";

                // Write each line to properly display new line characters.
                while (($line = fgets($handle)) !== false) {
                    $output .= "<div>" . nl2br($line) . "</div>";
                }

                $output .= "</div>";
            }
        }

        $output .= "</body></html>";

        return $output;
    }
}