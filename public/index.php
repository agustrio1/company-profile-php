<?php

/**
 * Company Profile Application
 * Entry Point
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\Session;
use App\Core\Log;
use Dotenv\Dotenv;

// Load ENV
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Error reporting
if (config('app.debug')) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Session
Session::start();

// Database
Database::connect();

// Request & Router
$request = new Request();
$router  = new Router();

// Routes
require dirname(__DIR__) . '/routes/web.php';
require dirname(__DIR__) . '/routes/admin.php';
require dirname(__DIR__) . '/routes/api.php';

try {
    $response = $router->dispatch($request);

    // JIKA ROUTE DITEMUKAN
    if ($response instanceof Response) {
        $response->send();
    } else {
        // 404 NOT FOUND
        Response::make()
            ->setStatusCode(404)
            ->view('errors.404')
            ->send();
    }

} catch (\Throwable $e) {

    // LOG ERROR (WAJIB)
    Log::error('Application Error', [
        'message' => $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
        'trace'   => $e->getTraceAsString()
    ]);

    // DEV MODE → tampilkan detail
    if (config('app.debug')) {
        throw $e;
    }

    // 500 INTERNAL SERVER ERROR
    Response::make()
        ->setStatusCode(500)
        ->view('errors.500')
        ->send();
}

// Clear flash
Session::clearFlash();
Session::clearOldInput();