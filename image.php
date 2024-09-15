<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['image_prompt'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No image prompt provided']);
    exit;
}

$imagePrompt = strtolower(trim($input['image_prompt']));

// Define some basic images
$images = [
    'sunset' => 'images/sunset.jpg',
    'forest' => 'images/forest.jpg',
    'mountain' => 'images/mountain.jpg',
];

$imagePath = isset($images[$imagePrompt]) ? $images[$imagePrompt] : 'images/default.jpg';

echo json_encode(['image_url' => 'http://192.168.0.80/' . $imagePath]);
?>
