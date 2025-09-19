<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a test request to the route
$request = Illuminate\Http\Request::create('/attractions/2', 'GET');

try {
    $response = $kernel->handle($request);
    echo "Response Status: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() === 404) {
        echo "Route not found. Checking available routes...\n";
        
        // Check if the route exists
        $router = app('router');
        $routes = $router->getRoutes();
        
        foreach ($routes as $route) {
            if (str_contains($route->uri(), 'attractions')) {
                echo "Found route: " . $route->methods()[0] . " " . $route->uri() . " -> " . $route->getActionName() . "\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

$kernel->terminate($request, $response ?? null);
