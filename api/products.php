<?php
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');

  require_once('../db.php');

  $method = $_SERVER['REQUEST_METHOD'];

  if ($method === 'GET') {

    echo json_encode(['success' => true, 'message' => 'Hello World!']);
  
  } elseif ($method === 'POST') {
  
  } else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
  }
