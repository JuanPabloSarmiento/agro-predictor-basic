<?php
// Simple route handler for /api endpoints

function handle_api($path) {
    // Normalize
    $path = rtrim($path, '/');

    if ($path === '/api/health') {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(["service" => "Agro Predictor API (basic scaffold)", "ok" => true]);
        return true;
    }

    // Other API endpoints could be added here

    return false;
}
