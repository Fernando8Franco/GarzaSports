<?php

// data-source.php

// Mock data array
$mockData = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
    ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
    ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com'],
    // ... more data
];

// Calculate the total number of pages based on the data and items per page
$itemsPerPage = 5;
$totalPages = ceil(count($mockData) / $itemsPerPage);

// Get the requested page number (default to 1)
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the starting and ending indices for the current page
$startIndex = ($page - 1) * $itemsPerPage;
$endIndex = min($startIndex + $itemsPerPage - 1, count($mockData) - 1);

// Get the data for the current page
$dataForPage = array_slice($mockData, $startIndex, $endIndex - $startIndex + 1);

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode([
    'data' => $dataForPage,
    'totalPages' => $totalPages,
]);
