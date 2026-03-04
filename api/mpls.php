<?php
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');

  require_once __DIR__ . '/../db.php';
  require __DIR__ . '/../functions/wms.php';

  $method = $_SERVER['REQUEST_METHOD'];
  $id = isset($_SERVER['PATH_INFO']) ? intval(ltrim($_SERVER['PATH_INFO'], '/')) : 0;

  // --- GET MPLS --- //
  if ($method === 'GET') {
    echo json_encode(['success' => true, 'message' => 'Hello World!']);
  
  // --- UPDATE MPLS + ADD INVENTORY --- //
  } elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // define required fields
    $data_keys = ['reference_number', 'trailer_number', 'expected_arrival', 'items'];


    // validate required fields
    foreach ($data_keys as $key) {
      if (!isset($data[$key])) {
        http_response_code(400);
        echo json_encode(['error' => 'Bad request', 'details' => 'Missing required MPL fields']);
        exit();
      }
    }

    // check for duplicate mpls
    if (get_mpl($data[$data_keys[0]])) {
      http_response_code(409);
      echo json_encode(['error' => 'Conflict', 'details' => 'MPL with this reference number already exists']);
      exit();
    }

    // finds and auto-creates missing sku
    // reassigns sku id if created
    $missing_skus = [];

    foreach ($data['items'] as $item) {
      $sku = get_sku_by_code($item['sku']);
      
      if (!$sku) {
        $new_sku_id = create_sku($item['sku_details']);

        if (!$new_sku_id) {
          $missing_skus[] = $item['sku'];
        }

        $sku_id = $new_sku_id;
      } else {
        $sku_id = $sku['id'];
      }
    }

    // error if created skus are missing data
    if (!empty($missing_skus)) {
      http_response_code(400);
      echo json_encode(['error' => 'Bad Request', 'details' => 'Missing SKUs in WMS: ' . implode(', ',$missing_skus) . '. Provide full SKU details to auto-create.']);
      exit();
    }

    // create mpl record
    $mpl_id = create_mpl($data);

    if ($mpl_id) {
      http_response_code(201);
      echo json_encode(['success' => true,'message' => "MPL received successfully", 'mpl_id' => $mpl_id]);
      exit();
      
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
