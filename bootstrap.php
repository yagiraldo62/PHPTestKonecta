<?php
require 'vendor/autoload.php';
use Dotenv\Dotenv;
use Src\DB\DatabaseConnector;
set_include_path(__DIR__);
//Load getter for .env values
$dotenv = new DotEnv(__DIR__);
$dotenv->load();

// Connect to DB
$dbConnection = (new DatabaseConnector())->getConnection();