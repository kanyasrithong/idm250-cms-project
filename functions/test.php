<?php
require_once 'wms.php';

// echo "<pre>";
// echo "<h3>Testing create_inventory()</h3>";

// $test_data = [
//     "order_number" => "ORD-TEST-001",
//     "unit_number" => "UNIT-99",
//     "ficha" => "F123", // make sure this ficha exists in sku_management
//     "description" => "Test Inventory Item",
//     "quantity_shipped" => 10,
//     "footage_quantity" => 25.5,
//     "ship_date" => date("Y-m-d")
// ];

// $result = create_inventory($test_data);

// if ($result) {
//     echo "Inventory Insert Successful<br><br>";
// } else {
//     echo "Insert Failed: " . $connection->error . "<br><br>";
// }

// echo "<h3>Testing get_inventory()</h3>";

// $inventory = get_inventory();

// if (!empty($inventory)) {
//     foreach ($inventory as $row) {
//         echo "ID: " . $row['id'] . "<br>";
//         echo "Order #: " . $row['order_number'] . "<br>";
//         echo "Ficha: " . $row['ficha'] . "<br>";
//         echo "SKU: " . ($row['sku'] ?? 'N/A') . "<br>";
//         echo "SKU Description: " . ($row['description'] ?? 'N/A') . "<br>";
//         echo "Quantity: " . $row['quantity_shipped'] . "<br>";
//         echo "Footage: " . $row['footage_quantity'] . "<br>";
//         echo "---------------------------<br>";
//     }
// } else {
//     echo "No inventory records found.";
// }

// echo "</pre>";

// // shows text in better format - keeps spaces & line breaks
// echo "<pre>";

// echo "<h2>Testing create_shipped_items()</h2>";

// // --- Test Data ---
// $test_data = [
//     'order_id' => 1, // make sure this order exists in your DB
//     'order_number' => 'ORD-1001',
//     'shipped_at' => date('Y-m-d H:i:s'),
//     'items' => [
//         [
//             'unit_id' => 'UNIT-001',
//             'sku' => 'SKU-ABC',
//             'description' => 'Test Product A'
//         ],
//         [
//             'unit_id' => 'UNIT-002',
//             'sku' => 'SKU-XYZ',
//             'description' => 'Test Product B'
//         ]
//     ]
// ];

// // --- Run Create Function ---
// $result = create_shipped_items($test_data);

// if ($result) {
//     echo "Shipped items inserted successfully.<br><br>";
// } else {
//     echo "Failed to insert shipped items.<br><br>";
// }

// echo "<h2>Testing get_shipped_items()</h2>";

// // --- Run Get Function ---
// $shipped = get_shipped_items();

// if (!empty($shipped)) {
//     echo "<pre>";
//     print_r($shipped);
//     echo "</pre>";
// } else {
//     echo "No shipped orders found (make sure order status is 'closed').";
// }


// echo "\nTESTING INVENTORY FUNCTIONS\n";

// $unit_id = 'UNIT06132005';   

// echo "Using unit_id: $unit_id\n\n";

// // --- Create inventory ---
// create_inventory($unit_id, 2);
// echo "Created $unit_id\n\n";

// // --- Show inventory ---
// echo "Inventory after insert:\n";
// print_r(get_inventory());

// // --- Delete inventory ---
// delete_inventory($unit_id);
// echo "\nDeleted $unit_id\n\n";

// // --- Show inventory again ---
// echo "Inventory after deletion:\n";
// print_r(get_inventory());

// echo "</pre>";

// echo "\n TESTING MPL FUNCTIONS \n";

// $mpl_data = [
//     'reference_number' => 'REF123',
//     'trailer_number'   => 'TR456',
//     'expected_arrival' => '2026-02-15',
//     'items' => [
//         ['unit_id' => 1, 'sku' => 'SKU001'],
//         ['unit_id' => 2, 'sku' => 'SKU002']
//     ]
// ];

// $mpl_id = create_mpl($mpl_data);

// if ($mpl_id === false) {
//     echo "Failed to create MPL<br>";
// } else {
//     echo "MPL created with ID: $mpl_id<br>";
// }


// $mpl = get_mpl('REF123');

// if ($mpl === null) {
//     echo "MPL not found<br>";
// } else {
//     echo "MPL found:<br>";
//     echo "<pre>";
//     print_r($mpl);
//     echo "</pre>";
// }

// echo "\n TESTING ORDER FUNCTIONS \n";
// $order_data = [
//     'order_number'    => 'ORD789',
//     'ship_to_company' => 'Test Company',
//     'ship_to_street'  => '123 Main St',
//     'ship_to_city'    => 'Philadelphia',
//     'ship_to_state'   => 'PA',
//     'ship_to_zip'     => '19104',
//     'items' => [
//         ['unit_id' => 3, 'sku' => 'SKU003'],
//         ['unit_id' => 4, 'sku' => 'SKU004']
//     ]
// ];

// $order_id = create_order($order_data);

// if ($order_id === false) {
//     echo "Failed to create Order<br>";
// } else {
//     echo "Order created with ID: $order_id<br>";
// }

// $order = get_order_by_number('ORD789');

// if ($order === null) {
//     echo "Order not found<br>";
// } else {
//     echo "Order found:<br>";
//     echo "<pre>";
//     print_r($order);
//     echo "</pre>";
// }



// echo "\n CREATING 3 SKUS \n";

// $sku1 = create_sku([
//     'ficha' => 'F101',
//     'sku' => 'SKU101',
//     'description' => 'Test item 1',
//     'uom_primary' => 'EA',
//     'piece_count' => 10,
//     'length_inches' => 5,
//     'width_inches' => 4,
//     'height_inches' => 3,
//     'weight_lbs' => 2,
// ]);

// $sku2 = create_sku([
//     'ficha' => 'F102',
//     'sku' => 'SKU102',
//     'description' => 'Test item 2',
//     'uom_primary' => 'EA',
//     'piece_count' => 15,
//     'length_inches' => 6,
//     'width_inches' => 5,
//     'height_inches' => 4,
//     'weight_lbs' => 3,
// ]);

// $sku3 = create_sku([
//     'ficha' => 'F103',
//     'sku' => 'SKU103',
//     'description' => 'Test item 3',
//     'uom_primary' => 'EA',
//     'piece_count' => 20,
//     'length_inches' => 7,
//     'width_inches' => 6,
//     'height_inches' => 5,
//     'weight_lbs' => 4,
// ]);

// // echo "Created IDs: $sku1, $sku2, $sku3\n";


// // echo "\n GETTING EACH BY ID \n";
// // print_r(get_sku($sku1));
// // print_r(get_sku($sku2));
// // print_r(get_sku($sku3));


// echo "\n GETTING EACH BY SKU CODE \n";
// print_r(get_sku_by_code('SKU101'));
// print_r(get_sku_by_code('SKU102'));
// print_r(get_sku_by_code('SKU103'));


// echo "\n GETTING ALL SKUS \n";
// print_r(get_skus());

// echo "</pre>";