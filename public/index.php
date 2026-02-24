<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';

use App\Core\Router;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;

// ---------------------------------------------------------------
//  Security Headers
// ---------------------------------------------------------------

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');

// ---------------------------------------------------------------
//  Start Session
// ---------------------------------------------------------------

Session::start();

// ---------------------------------------------------------------
//  Route & Dispatch
// ---------------------------------------------------------------

$router  = new Router();
$request = new Request();

require CONFIG_PATH . '/routes.php';

$router->dispatch($request);
