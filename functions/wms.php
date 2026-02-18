<?php

require_once '../db.php';

// --- SKU FUNCTIONS --- //
// creating a sku
function create_sku($data) {
    global $connection;

    $ficha = $connection->real_escape_string($data['ficha']);
    $sku = $connection->real_escape_string($data['sku']);
    $desc = $connection->real_escape_string($data['description']);
    $uom = $connection->real_escape_string($data['uom_primary']);
    $piece_count = intval($data['piece_count']);
    $length = floatval($data['length_inches']);
    $width = floatval($data['width_inches']);
    $height = floatval($data['height_inches']);
    $weight = floatval($data['weight_lbs']);
    $assembly = $connection->real_escape_string($data['assembly']);
    $rate = floatval($data['rate']);

    $stmt = $connection->prepare("INSERT INTO sku_management 
        (ficha, sku, `description`, uom_primary, piece_count, length_inches, width_inches, height_inches, weight_lbs, `assembly`, rate)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        'ssssiddddsd', $ficha, $sku, $desc, $uom, $piece_count, $length, $width, $height, $weight, $assembly, $rate
    );

    if ($stmt->execute())
        return $connection->insert_id;

    return false;
}

// getting skus
function get_skus() {
    global $connection;

    $stmt = $connection->prepare("SELECT * FROM sku_management");
    $stmt->execute();

    $result = $stmt->get_result();
    $skus = [];

    while ($row = $result->fetch_assoc()) {
        $skus[] = $row;
    }

    return $skus;
}

// getting skus with an id
function get_sku($id) {
    global $connection;

    $id = intval($id);
    $stmt = $connection->prepare("SELECT * FROM sku_management WHERE id = ? LIMIT 1");

    $stmt->bind_param('i', $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result == false) return null;
    return $result->fetch_assoc();
}

// getting skus with a code
function get_sku_by_code($sku) {
    global $connection;

    $stmt = $connection->prepare("SELECT * FROM sku_management WHERE sku = ? LIMIT 1");

    $stmt->bind_param('s', $sku);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result == false) return null;
    return$result->fetch_assoc();
}

// updating skus
function update_sku($id, $data) {
    global $connection;

    $id = intval($id);
    $ficha = $connection->real_escape_string($data['ficha']);
    $sku = $connection->real_escape_string($data['sku']);
    $desc = $connection->real_escape_string($data['description']);
    $uom = $connection->real_escape_string($data['uom_primary']);
    $piece_count = intval($data['piece_count']);
    $length = floatval($data['length_inches']);
    $width = floatval($data['width_inches']);
    $height = floatval($data['height_inches']);
    $weight = floatval($data['weight_lbs']);
    $assembly = $connection->real_escape_string($data['assembly']);
    $rate = floatval($data['rate']);

    $stmt = $connection->prepare("UPDATE sku_management 
        SET ficha = ?, sku = ?, `description` = ?, 
            uom_primary = ?, piece_count = ?, length_inches = ?, width_inches = ?,
            height_inches = ?, weight_lbs = ?, `assembly` = ?, rate = ?
        WHERE id = ? LIMIT 1");

    $stmt->bind_param('ssssiddddssi', $ficha, $sku, $desc, $uom, $piece_count, $length, $width, $height, $weight, $assembly, $rate, $id);

    return $stmt->execute();
}

// deleting skus
function delete_sku($id) {
    global $connection; 

    $id = intval($id);
    $stmt = $connection->prepare("DELETE FROM sku_management WHERE id = ? LIMIT 1");

    $stmt->bind_param('i', $id);
    return $stmt->execute();
}



// --- MPL FUNCTIONS --- //
// creating an MPL
function create_mpl($data) {
    global $connection;

    $reference = $connection->real_escape_string($data['reference_number']);
    $trailer = $connection->real_escape_string($data['trailer_number']);
    $arrival = $connection->real_escape_string($data['expected_arrival']);
    $items = $data['items'];

    $stmt = $connection->prepare("INSERT INTO mpls 
        (reference_number, trailer_number, expected_arrival, `status`)
        VALUES (?, ?, ?, 'open')");

    $stmt->bind_param('sss', $reference, $trailer, $arrival);
    if (!$stmt->execute())
        return false;

    $mpl_id = $connection->insert_id;

    $stmt = $connection->prepare("INSERT INTO mpl_items 
        (mpl_id, unit_id, sku)
        VALUES (?, ?, ?)");

    foreach ($items as $item) {
        $unit_id = intval($item['unit_id']);
        $sku = $connection->real_escape_string($item['sku']);

        $stmt->bind_param('iis', $mpl_id, $unit_id, $sku);
        if(!$stmt->execute())
            return false;
    }

    return $mpl_id;
}

// looking up an MPL by reference number
function get_mpl($reference_number) {
    global $connection;

    $reference_number = $connection->real_escape_string($reference_number);

    $stmt = $connection->prepare("SELECT * FROM mpls WHERE reference_number = ? LIMIT 1");
    $stmt->bind_param('s', $reference_number);

    $stmt->execute();

    $result = $stmt->get_result();

    if($result == false || $result->num_rows === 0) return null;
    
    return $result->fetch_assoc();
}



// --- ORDER FUNCTIONS --- //
// creating an order
function create_order($data) {
    global $connection;

    $order_number = $connection->real_escape_string($data['order_number']);
    $ship_to_company = $connection->real_escape_string($data['ship_to_company']);
    $ship_to_street = $connection->real_escape_string($data['ship_to_street']);
    $ship_to_city = $connection->real_escape_string($data['ship_to_city']);
    $ship_to_state = $connection->real_escape_string($data['ship_to_state']);
    $ship_to_zip = $connection->real_escape_string($data['ship_to_zip']);
    $items = $data['items'];

    $stmt = $connection->prepare("INSERT INTO orders 
        (order_number, ship_to_company, ship_to_street, ship_to_city, ship_to_state, ship_to_zip, `status`)
        VALUES (?, ?, ?, ?, ?, ?, 'open')");

    $stmt->bind_param('ssssss', $order_number, $ship_to_company, $ship_to_street, $ship_to_city, $ship_to_state, $ship_to_zip);
    if (!$stmt->execute())
        return false;

    $order_id = $connection->insert_id;

    $stmt = $connection->prepare("INSERT INTO order_items 
        (order_id, unit_id, sku)
        VALUES (?, ?, ?)");

    foreach ($items as $item) {
        $unit_id = intval($item['unit_id']);
        $sku = $connection->real_escape_string($item['sku']);

        $stmt->bind_param('iis', $order_id, $unit_id, $sku);
        if(!$stmt->execute())
            return false;
    }

    return $order_id;
}

// looking up an order by number
function get_order_by_number($order_number) {
    global $connection;

    $order_number = $connection->real_escape_string($order_number);

    $stmt = $connection->prepare("SELECT * FROM orders WHERE order_number = ? LIMIT 1");
    $stmt->bind_param('s', $order_number);

    $stmt->execute();

    $result = $stmt->get_result();

    if($result == false || $result->num_rows === 0) return null;
    
    return $result->fetch_assoc();
}



// --- INVENTORY FUNCTIONS --- //
// creating inventory
function create_inventory($unit_id, $sku_id) {
    global $connection;
    
    $unit_id = $connection->real_escape_string($unit_id);
    $sku_id = intval($sku_id);

    $stmt = $connection->prepare("INSERT INTO inventory 
        (unit_id, sku_id)
        VALUES (?, ?)");

    $stmt->bind_param('si', $unit_id, $sku_id);

    return $stmt->execute();
}

// getting inventory
function get_inventory() {
    global $connection;

    $sql = "SELECT i.*, s.sku, s.description, s.uom_primary
        FROM inventory i
        JOIN sku_management s ON i.sku_id = s.id
        ORDER BY i.created_at DESC";

    $stmt = $connection->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();
    $inventory = [];

    while ($row = $result->fetch_assoc()) {
        $inventory[] = $row;
    }

    return $inventory;
}

// deleting inventory
function delete_inventory($unit_id) {
    global $connection;

    $unit_id = $connection->real_escape_string($unit_id);
    $stmt = $connection->prepare("DELETE FROM inventory WHERE unit_id = ? LIMIT 1");

    $stmt->bind_param('s', $unit_id);
    return $stmt->execute();
}



// --- SHIPPED ITEMS FUNCTIONS --- //
// creating shipped items

// getting shipped items
?>