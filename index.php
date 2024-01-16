<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/autoload.php';

(new AutoLoader())->register();

use Services\CreateControllerService;
use Services\LoaderService;

session_start();

(new LoaderService)
    ->loadVendorCode()
    ->startupProjectConfig();

$router = Router::getRouter($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

$content = (new CreateControllerService())->getMethodContent($router);
CreateControllerService::printContent($content);

