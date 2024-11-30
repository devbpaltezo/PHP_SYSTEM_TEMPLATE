<?php

    // autoload.php
    function autoloadFunctions() {
        // Define directories containing your function files
        $directories = [
            __DIR__ . '/utilities',
            __DIR__ . '/db',
            __DIR__ . '/providers'
        ];

        // Loop through each directory and include all PHP files
        foreach ($directories as $directory) {
            // Check if the directory exists
            if (is_dir($directory)) {
                // Get all PHP files in the directory
                $files = glob($directory . '/*.php');
                foreach ($files as $file) {
                    include_once $file; // Include the function file
                }
            }
        }
    }


?>