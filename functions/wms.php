<?php

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../includes/api_client.php';

// --- SKU FUNCTIONS --- //
// creating a sku
function create_sku($data) {
    global $connection;

    $required_fields = [
        'ficha',
        'sku',
        'description',
        'uom_primary',
        'piece_count',
        'length_inches',
        'width_inches',
        'height_inches',
        'weight_lbs'
    ];

    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            return false;
        }
    }

    $ficha = $connection->real_escape_string($data['ficha']);
    $sku = $connection->real_escape_string($data['sku']);
    $desc = $connection->real_escape_string($data['description']);
    $uom = $connection->real_escape_string($data['uom_primary']);
    $piece_count = intval($data['piece_count']);
    $length = floatval($data['length_inches']);
    $width = floatval($data['width_inches']);
    $height = floatval($data['height_inches']);
    $weight = floatval($data['weight_lbs']);

    $stmt = $connection->prepare("INSERT INTO sku_management 
        (ficha, sku, `description`, uom_primary, piece_count, length_inches, width_inches, height_inches, weight_lbs)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        'ssssidddd', $ficha, $sku, $desc, $uom, $piece_count, $length, $width, $height, $weight
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

    $stmt = $connection->prepare("UPDATE sku_management 
        SET ficha = ?, sku = ?, `description` = ?, 
            uom_primary = ?, piece_count = ?, length_inches = ?, width_inches = ?,
            height_inches = ?, weight_lbs = ?
        WHERE id = ? LIMIT 1");

    $stmt->bind_param('ssssiddddi', $ficha, $sku, $desc, $uom, $piece_count, $length, $width, $height, $weight, $id);

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
        (mpl_id, unit_number, sku)
        VALUES (?, ?, ?)");

    foreach ($items as $item) {
        $unit_number = $item['unit_number'];
        $sku = $connection->real_escape_string($item['sku']);

        $stmt->bind_param('iss', $mpl_id, $unit_number, $sku);
        if(!$stmt->execute())
            return false;
    }

    return $mpl_id;
}

// getting mpls
function get_mpls() {
    global $connection;

    $stmt = $connection->prepare("SELECT * FROM mpls");
    $stmt->execute();

    $result = $stmt->get_result();
    $mpls = [];

    while ($row = $result->fetch_assoc()) {
        $mpls[] = $row;
    }

    return $mpls;
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

// get mpl items by mpl id
function get_mpl_items($mpl_id) {
    global $connection;

    $mpl_id = intval($mpl_id);

    $stmt = $connection->prepare(
        "SELECT mi.*, s.sku, s.description
        FROM mpl_items mi
        JOIN sku_management s ON mi.sku = s.sku
        WHERE mi.mpl_id = ?"
    );
    $stmt->bind_param('i', $mpl_id);

    $stmt->execute();
    
    $result = $stmt->get_result();
    $mpl_items = [];

    while ($row = $result->fetch_assoc()) {
        $mpl_items[] = $row;
    }

    $stmt->close();
    return $mpl_items;
}

function update_mpl_status($mpl_id) {
    global $connection;

    $stmt = $connection->prepare("UPDATE mpls SET `status` = 'closed' WHERE id = ?");
    $stmt->bind_param('i', $mpl_id);

    $stmt->execute();
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
        (order_id, unit_number, sku)
        VALUES (?, ?, ?)");

    foreach ($items as $item) {
        // finds item details in inventory
        $find_item = get_inventory_by_unit_number($item['unit_number']);

        $unit_number = $connection->real_escape_string($find_item['unit_number']);
        $sku = $connection->real_escape_string($find_item['sku']);

        $stmt->bind_param('iss', $order_id, $unit_number, $sku);
        if(!$stmt->execute())
            return false;
    }

    return $order_id;
}

// getting orders
function get_orders() {
    global $connection;

    $stmt = $connection->prepare("SELECT * FROM orders");
    $stmt->execute();

    $result = $stmt->get_result();
    $orders = [];

    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    return $orders;
}

// looking up an order by id
function get_order($order_id) {
    global $connection;

    $order_id = $connection->real_escape_string($order_id);

    $stmt = $connection->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
    $stmt->bind_param('s', $order_id);

    $stmt->execute();

    $result = $stmt->get_result();

    if($result == false || $result->num_rows === 0) return null;
    
    return $result->fetch_assoc();
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

// get order items by order id
function get_order_items($order_id) {
    global $connection;

    $order_id = intval($order_id);

    $stmt = $connection->prepare(
        "SELECT oi.*, i.sku, s.sku, s.description
        FROM order_items oi
        JOIN inventory i ON oi.unit_number = i.unit_number
        JOIN sku_management s ON i.sku = s.sku
        WHERE oi.order_id = ?"
    );
    $stmt->bind_param('i', $order_id);
    
    $stmt->execute();

    $result = $stmt->get_result();
    $order_items = [];

    while ($row = $result->fetch_assoc()) {
        $order_items[] = $row;
    }

    $stmt->close();
    return $order_items;
}

function update_order_status($id) {
    global $connection;

    $id = intval($id);
    $shipped_at = date("Y-m-d G:i:s");

    $stmt = $connection->prepare("UPDATE orders SET `status` = 'closed', shipped_at = ? WHERE id = ?");
    $stmt->bind_param('si', $shipped_at, $id);

    $stmt->execute();
    return $shipped_at;
};

// --- INVENTORY FUNCTIONS --- //
// creating inventory
function create_inventory($data) {
    global $connection;
    
    $unit_number = $connection->real_escape_string($data['unit_number']);
    $sku = $connection->real_escape_string($data['sku']);

    $stmt = $connection->prepare("INSERT INTO inventory 
        (unit_number, sku)
        VALUES (?, ?)");

    $stmt->bind_param('ss', $unit_number, $sku);

    return $stmt->execute();
}

// getting inventory
function get_inventory() {
    global $connection;

    $sql = "SELECT i.*, s.sku, s.description, s.uom_primary
        FROM inventory i
        JOIN sku_management s ON i.sku = s.sku
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

// getting inventory from unit number
function get_inventory_by_unit_number($unit_number) {
    global $connection;

    $unit_number = $connection->real_escape_string($unit_number);

    $stmt = $connection->prepare("SELECT * FROM inventory WHERE unit_number = ? LIMIT 1");
    $stmt->bind_param('s', $unit_number);

    $stmt->execute();

    $result = $stmt->get_result();

    if($result == false || $result->num_rows === 0) return null;
    
    return $result->fetch_assoc();
}

// deleting inventory
function delete_inventory($unit_number) {
    global $connection;

    $unit_number = $connection->real_escape_string($unit_number);
    $stmt = $connection->prepare("DELETE FROM inventory WHERE unit_number = ? LIMIT 1");

    $stmt->bind_param('s', $unit_number);
    return $stmt->execute();
}

// --- SHIPPED ITEMS FUNCTIONS --- //
// creating shipped items
function create_shipped_items($data) {
    global $connection;

    $order_id = intval($data['order_id']);

    // get order details
    $order = get_order($order_id);
    $order_number = $connection->real_escape_string($order['order_number']);
    $items = get_order_items($order_id);
    $shipped_at = date("Y-m-d G:i:s");

    $stmt = $connection->prepare("INSERT INTO shipped_items 
        (order_id, order_number, unit_number, sku, shipped_at)
        VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        return false;
    }

    foreach ($items as $item) {
        $stmt->bind_param('issss', $order_id, $order_number, $item['unit_number'], $item['sku'], $shipped_at);
        if (!$stmt->execute()) {
            return false;
        }
    }

    return true;
}

// getting shipped items
function get_shipped_items() {
    global $connection;

    $sql = "SELECT o.order_number, o.ship_to_company, si.shipped_at, COUNT(*) as item_count
        FROM orders o
        JOIN shipped_items si ON o.id = si.order_id
        WHERE o.status = 'closed'
        GROUP BY o.id, o.order_number, o.ship_to_company, si.shipped_at
        ORDER BY si.shipped_at DESC";

    $stmt = $connection->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();
    $shipped_items = [];

    while($row = $result->fetch_assoc()) {
        $shipped_items[] = $row;
    }
    
    return $shipped_items;
}

function get_shipped_items_by_number($order_number) {
    global $connection;

    $order_number = $connection->real_escape_string($order_number);

    $stmt = $connection->prepare("SELECT * FROM shipped_items WHERE order_number = ?");
    $stmt->bind_param('s', $order_number);

    $stmt->execute();

    $result = $stmt->get_result();

    if($result == false || $result->num_rows === 0) return [];
    
    return $result->fetch_all(MYSQLI_ASSOC);;
}

function confirm_mpl($reference_number) {
    $mpl = get_mpl($reference_number);

    if (!$mpl) {
        return ['success' => false, 'message' => 'MPL not found'];
    }

    $mpl_id = intval($mpl['id']);
    $mpl_items = get_mpl_items($mpl_id);

    foreach ($mpl_items as $mpl_item) {
        get_sku_by_code($mpl_item['sku']);
        create_inventory($mpl_item);
    }

    update_mpl_status($mpl_id);

    notify_cms_mpl_confirmed($reference_number);

    return ['success' => true];
};

function ship_order($order_number) {
    $order = get_order_by_number($order_number);

    if (!$order) {
        return ['success' => false, 'message' => 'Order not found'];
    }

    $order_id = intval($order['id']);
    $order_items = get_order_items($order_id);

    if (empty($order_items)) {
        return ['success' => false, 'message' => 'No items found for this order'];
    }

    create_shipped_items(['order_id' => $order_id]);

    foreach ($order_items as $order_item) {
        delete_inventory($order_item['unit_number']);
    }

    // updates status + returns shipped at date
    $shipped_at = update_order_status($order_id);
    notify_cms_order_shipped($order['order_number'], $shipped_at);

    return ['success' => true];
}
