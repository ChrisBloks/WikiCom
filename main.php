<?php
include 'vendor/autoload.php';

use Wiki\controllers\MainController;
   
session_start();
$controller = new MainController();
$controller->main();
