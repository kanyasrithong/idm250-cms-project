<?php
require_once 'wms.php';

// shows text in better format - keeps spaces & line breaks
echo "<pre>";

echo "\n CREATING 3 SKUS \n";

$sku1 = create_sku([
    'ficha' => 'F101',
    'sku' => 'SKU101',
    'description' => 'Test item 1',
    'uom_primary' => 'EA',
    'piece_count' => 10,
    'length_inches' => 5,
    'width_inches' => 4,
    'height_inches' => 3,
    'weight_lbs' => 2,
    'assembly' => 'No',
    'rate' => 1.25
]);

$sku2 = create_sku([
    'ficha' => 'F102',
    'sku' => 'SKU102',
    'description' => 'Test item 2',
    'uom_primary' => 'EA',
    'piece_count' => 15,
    'length_inches' => 6,
    'width_inches' => 5,
    'height_inches' => 4,
    'weight_lbs' => 3,
    'assembly' => 'Yes',
    'rate' => 2.50
]);

$sku3 = create_sku([
    'ficha' => 'F103',
    'sku' => 'SKU103',
    'description' => 'Test item 3',
    'uom_primary' => 'EA',
    'piece_count' => 20,
    'length_inches' => 7,
    'width_inches' => 6,
    'height_inches' => 5,
    'weight_lbs' => 4,
    'assembly' => 'No',
    'rate' => 3.75
]);

echo "Created IDs: $sku1, $sku2, $sku3\n";


echo "\n GETTING EACH BY ID \n";
print_r(get_sku($sku1));
print_r(get_sku($sku2));
print_r(get_sku($sku3));


echo "\n GETTING EACH BY SKU CODE \n";
print_r(get_sku_by_code('SKU101'));
print_r(get_sku_by_code('SKU102'));
print_r(get_sku_by_code('SKU103'));


echo "\n GETTING ALL SKUS \n";
print_r(get_skus());

echo "</pre>";