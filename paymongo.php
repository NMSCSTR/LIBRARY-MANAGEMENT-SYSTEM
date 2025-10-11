
<?php

// Endpoint URL
$url = 'https://generalao-resort.online/source/create_source.php'; // Change to your actual server

// Data to send
$data = [
    'amount' => 10000,
    'type' => 'gcash',
    'currency' => 'PHP',
    'redirect' => [
        'success' => 'http://localhost/app/index.php',
        'failed' => 'http://localhost/app/index.php'
    ],
    'billing' => [
        'name' => 'Juan Dela Cruz',
        'email' => 'juan@example.com',
        'phone' => '09171112222'
    ]
];

// Convert data to JSON
$jsonData = json_encode($data);

// Initialize cURL
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Developer-Key: dev_123456'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

// Execute request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Request Error: ' . curl_error($ch);
} else {
    // Decode and print response
    $result = json_decode($response, true);
    echo "<pre>";
    print_r($result);
    echo "</pre>";
}

// Close cURL
curl_close($ch);


// endpoint

$headers = getallheaders();
file_put_contents('headers_log.txt', print_r($headers, true));

// Set your secret PayMongo API key here
$PAYMONGO_SECRET = 'Basic c2tfbGl2ZV9EOThaY0h3ajVZMzU1SDQxMWtRYUVkQlY6c2tfbGl2ZV9EOThaY0h3ajVZMzU1SDQxMWtRYUVkQlY=';

// Simple API key list for devs (key => allowed redirect domains)
$DEVELOPER_KEYS = [
    'dev_123456' => ['localhost'],
    'dev_abcdef' => ['yourtester.dev']
];

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST allowed']);
    exit;
}

// Parse headers
$headers = getallheaders();
// $devKey = $headers['X-Developer-Key'] ?? null;
$devKey = $headers['X-Developer-Key'] ?? $headers['x-developer-key'] ?? null;


if (!$devKey || !isset($DEVELOPER_KEYS[$devKey])) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid or missing developer key']);
    exit;
}


// Read and parse JSON body
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['amount'], $input['type'], $input['currency'], $input['redirect'], $input['billing'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Validate redirect domain
$successHost = parse_url($input['redirect']['success'], PHP_URL_HOST);
$failedHost = parse_url($input['redirect']['failed'], PHP_URL_HOST);
$allowedDomains = $DEVELOPER_KEYS[$devKey];

if (!in_array($successHost, $allowedDomains) || !in_array($failedHost, $allowedDomains)) {
    http_response_code(403);
    echo json_encode(['error' => 'Redirect domain not allowed']);
    exit;
}

// Prepare PayMongo request
$data = [
    'data' => [
        'attributes' => [
            'amount' => $input['amount'],
            'type' => $input['type'],
            'currency' => $input['currency'],
            'redirect' => [
                'success' => $input['redirect']['success'],
                'failed' => $input['redirect']['failed']
            ],
            'billing' => [
                'name' => $input['billing']['name'],
                'email' => $input['billing']['email'],
                'phone' => $input['billing']['phone']
            ]
        ]
    ]
];

$ch = curl_init('https://api.paymongo.com/v1/sources');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Basic c2tfbGl2ZV9EOThaY0h3ajVZMzU1SDQxMWtRYUVkQlY6c2tfbGl2ZV9EOThaY0h3ajVZMzU1SDQxMWtRYUVkQlY='
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$response = curl_exec($ch);
curl_close($ch);

// Parse PayMongo response
$result = json_decode($response, true);
$checkoutUrl = $result['data']['attributes']['redirect']['checkout_url'] ?? null;

if ($checkoutUrl) {
    header("Location: $checkoutUrl");
    exit;
} else {
       http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to get checkout_url']);
    exit;
}
