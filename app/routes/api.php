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
    // POST /api/predict - forwards request to ML service and returns its response
    if ($path === '/api/predict' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?: [];

        // Basic validation
        if (!isset($data['ph']) || !isset($data['area'])) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Missing "ph" or "area" in JSON body']);
            return true;
        }

        // Prepare payload for ML service
        $payload = json_encode(['ph' => $data['ph'], 'area' => $data['area']]);

        // Try calling ML service inside Docker network (service name: ml)
        $ml_url = getenv('ML_URL') ?: 'http://ml:5000/predict';

        $ch = curl_init($ml_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($errno) {
            http_response_code(502);
            echo json_encode(['ok' => false, 'error' => 'Failed to contact ML service', 'curl_error' => $errno]);
            return true;
        }

        // Forward ML response (attempt to keep status code)
        if ($http_code >= 200 && $http_code < 300) {
            http_response_code(200);
        } else {
            http_response_code($http_code ?: 502);
        }

        // If ML returned JSON, pass it through; otherwise return raw body
        $decoded = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo json_encode($decoded);
        } else {
            echo json_encode(['ok' => false, 'error' => 'Invalid response from ML service', 'body' => $response]);
        }

        return true;
    }

    // Simple test endpoint to check connectivity to ML from PHP container
    if ($path === '/api/test-ml') {
        header('Content-Type: application/json');
        $ml_url = getenv('ML_URL') ?: 'http://ml:5000/predict';
        // Ping ML service with default example payload
        $payload = json_encode(['ph' => 6.5, 'area' => 1]);
        $ch = curl_init($ml_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($errno) {
            http_response_code(502);
            echo json_encode(['ok' => false, 'error' => 'Failed to contact ML service', 'curl_error' => $errno]);
            return true;
        }

        http_response_code($http_code ?: 200);
        echo $response ?: json_encode(['ok' => false, 'error' => 'No response body']);
        return true;
    }

    return false;
}
