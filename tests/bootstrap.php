<?php

require_once __DIR__ . '/../vendor/autoload.php';

// configure environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

$_SERVER['REQUEST_TIME'] = 1234567890;
$_ENV = $_GET = $_POST = [];
