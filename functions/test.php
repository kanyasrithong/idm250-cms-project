<?php
require_once 'wms.php';

// shows text in better format - keeps spaces & line breaks
echo "<pre>";

echo "\n GETTING ALL SKUS \n";
print_r(get_skus());

echo "\n GETTING SKU AT ID = 1 \n";
print_r(get_sku(1));

echo "\n UPDATING SKU AT ID = 1 \n";
$data = [
    'ficha' => 'F999',
    'sku' => 'SKU999',
    'description' => 'Updated test item',
    'uom_primary' => 'EA',
    'piece_count' => 20,
    'length_inches' => 10,
    'width_inches' => 10,
    'height_inches' => 10,
    'weight_lbs' => 5,
    'assembly' => 'Yes',
    'rate' => 3.25
];

update_sku(1, $data);

echo "\n AFTER UPDATE \n";
print_r(get_sku(1));

$new_id = create_sku([
    'ficha' => 'F777',
    'sku' => 'SKU777',
    'description' => 'Created item',
    'uom_primary' => 'EA',
    'piece_count' => 5,
    'length_inches' => 2,
    'width_inches' => 2,
    'height_inches' => 2,
    'weight_lbs' => 1,
    'assembly' => 'No',
    'rate' => 0.99
]);

echo "New ID: " . $new_id . "\n";
print_r(get_sku($new_id));

// DELETING IS PERMANENT 
// delete_sku(1);

// echo "\n---- AFTER DELETE ----\n";
// print_r(get_sku(1));


echo "</pre>";