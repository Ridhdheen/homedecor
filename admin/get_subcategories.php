<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

if (isset($_GET['category_id'])) {
    $subcategories = getCategories($_GET['category_id']);
    echo json_encode($subcategories);
} else {
    echo json_encode([]);
}