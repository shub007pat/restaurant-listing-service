<?php
// Include database configuration
require 'config/database.php';

// Get the HTTP method, URI, and any data sent
$method = $_SERVER['REQUEST_METHOD'];

$request_uri = $_SERVER['REQUEST_URI'];

// Extract the path from REQUEST_URI
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove any base path from the path
$base_path = '/restaurant-listing-service'; // Update this to your actual base path
$path = str_replace($base_path, '', $path);

// Parse the path to determine the request
$request = explode('/', trim($path, '/'));

// Connect to the database
$db = new Database();

// Retrieve restaurants
if ($method === 'GET' && $request[0] === 'restaurants' && isset($_GET['id'])) {
    // To get the restaurant details, pass the id as a query parameter
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    
    if ($id) {
        $restaurant = $db->getRestaurantDetails($id);
        echo json_encode($restaurant);
    } else {
        // Return an error or handle the case where id is missing
        echo json_encode(array('error' => 'Restaurant ID is missing'));
    }
} else if ($method == 'GET' && $request[0] === 'restaurants') {
    $restaurants = $db->getRestaurants();
    echo json_encode($restaurants);
} elseif ($method == 'POST' && $request[1] === 'restaurants') { // Add a new restaurant
    $input = json_decode(file_get_contents('php://input'), true);

    // Insert the new restaurant into the database
    $success = $db->addRestaurant($input);

    if ($success) {
        echo json_encode(array('message' => 'Restaurant added successfully'));
    } else {
        echo json_encode(array('message' => 'Failed to add restaurant'));
    }
} 