<?php
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');

  require_once __DIR__ . '/../db.php';
  require __DIR__ . '/../functions/wms.php';

  $method = $_SERVER['REQUEST_METHOD'];
  $id = isset($_SERVER['PATH_INFO']) ? intval(ltrim($_SERVER['PATH_INFO'], '/')) : 0;

  if ($method === 'GET') {
    $skus = get_skus();

    if ($id > 0) :
      $sku = get_sku($id);

      if ($sku) {
        echo json_encode(['success' => true, 'data' => $sku]);
      } else {
        http_response_code(404);

        echo json_encode(['error' => 'Product not found', 'message' => 'Number of available SKUs: ' .  count($skus)]);
        exit();
      }
    else :
      http_response_code(201);
      echo json_encode(['success' => true, 'data' => $skus]);
    endif;
  }