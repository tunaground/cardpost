<?php
require('../vendor/autoload.php');

session_start();
$application = include('../bootstrap/Application.php');
$application->run();
session_write_close();