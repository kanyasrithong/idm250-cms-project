<?php

require_once '../db.php';

// SKU FUNCTIONS
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
        (ficha, sku, description, uom_primary, piece_count, length_inches, width_inches, height_inches, weight_lbs, assembly, rate)
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
    return$result->fetch_assoc();
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
        SET ficha = ?, sku = ?, description = ?, 
            uom_primary = ?, piece_count = ?, length_inches = ?, width_inches = ?,
            height_inches = ?, weight_lbs = ?, assembly = ?, rate = ?
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
?>