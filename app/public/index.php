<?php
// Simple index.php that loads routes and dispatches basic API paths
header('Content-Type: application/json');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Include api routes
$routes_file = __DIR__ . '/../routes/api.php';
if (file_exists($routes_file)) {
    require_once $routes_file;
}

// If request starts with /api, let routes handle it
if (strpos($uri, '/api') === 0) {
    if (function_exists('handle_api')) {
        $handled = handle_api($uri);
        if ($handled) {
            exit;
        }
    }
    http_response_code(404);
    echo json_encode(["error" => "API route not found", "ok" => false]);
    exit;
}

// Default response for root or other pages
echo json_encode(["service" => "Agro Predictor API (basic scaffold)", "ok" => true]);
