<?php
// run with php -S localhost:8000 fake_isbndb_api.php
// fake_isbndb_api.php

// Check if the request is for the book with the specific ISBN

if ($_SERVER['REQUEST_URI'] === '/book/NEW-123-test') {
    header('Content-Type: application/json');
    echo file_get_contents('books/123-test.json');
} else {
    // If the ISBN does not match, return a 404 Not Found response
    http_response_code(404);
    echo json_encode(['error' => 'Book not found']);
}
