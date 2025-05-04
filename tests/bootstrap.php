<?php
// Set error reporting to all errors
error_reporting(E_ALL);

// Define base path constant
define('BASE_PATH', dirname(__DIR__));

// Include the autoloader
require_once BASE_PATH . '/vendor/autoload.php';

// Include database configuration
require_once BASE_PATH . '/config/database.php';