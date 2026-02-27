<?php
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');

  require_once __DIR__ . '/../db.php';
  require __DIR__ . '/../functions/wms.php';

  $method = $_SERVER['REQUEST_METHOD'];
  $id = isset($_SERVER['PATH_INFO']) ? intval(ltrim($_SERVER['PATH_INFO'], '/')) : 0;

  if ($method === 'GET') {
    
    echo json_encode(['success' => true, 'message' => 'Hello World!']);
  
  // --- UPDATE ORDERS --- //
  } elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // define required fields
    $data_keys = ['order_number', 'ship_to_company', 'ship_to_street', 'ship_to_city', 'ship_to_state', 'ship_to_zip', 'items'];

    // validate required fields
    foreach ($data_keys as $key) {
      if (!isset($data[$key])) {
        http_response_code(400);
        echo json_encode(['error' => 'Bad request', 'details' => 'Missing required order fields']);
        exit();
      }
    }

    // check for duplicate orders
    if (get_order_by_number($data[$data_keys[0]])) {
      http_response_code(409);
      echo json_encode(['error' => 'Conflict', 'details' => 'Order with this reference number already exists']);
      exit();
    }

    // error if each unit_id doesn't exist in inventory
    $missing_items = [];

    foreach ($data['items'] as $item) {
      $unit_number = get_inventory_by_unit_number($item['unit_number']);
    
      if (!$unit_number) {
        $missing_items[] = $item['unit_number'];
      }

      if ($missing_items) {
        http_response_code(400);
        echo json_encode(['error' => 'Bad Request', 'details' => 'Units not in WMS inventory: ' . implode(', ', $missing_items)]);
        exit();
      }
    }

    // creating order record
    $order_id = create_order($data);

    if ($order_id) {
      http_response_code(201);
      echo json_encode(['success' => true,'message' => "Order received successfully", 'mpl_id' => $order_id]);
    } else {
      http_response_code(500);
      echo json_encode(['error' => 'Server Error']);
      exit();
    }

  } else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
  }

