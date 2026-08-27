<?php
/**
 * Main entry point for the Mbowo Camp website.
 *
 * This file redirects all traffic from the root directory
 * to the main homepage located in the /pages/ directory.
 */

// The base path of the project.
$base_path = '/chalochesu';

// Perform a permanent redirect (301) to the homepage.
header("Location: " . $base_path . "/pages/index.php", true, 301);
exit();