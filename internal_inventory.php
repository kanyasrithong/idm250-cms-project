<?php
  session_start();
  require_once "db.php";
  require_once "functions/auth.php";
  require "functions/wms.php";
  require_login();
  $page = "internal_inventory";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory</title>
  <link rel="stylesheet" href="styles/normalize.css">
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
  <?php 
    include "components/header.php";
    include "components/nav.php";
    $inventory_items = get_inventory();
  ?>
  <main>
    <div id="title">
      <h2>Inventory</h2>
      <h3><?= count($inventory_items) ?> orders</h3>
    </div>
    <table>
      <tr>
        <th>ORDER NUMBER</th>
        <th>UNIT NUMBER</th>
        <th>FICHA</th>
        <th>DESCRIPTION</th>
        <th>QUANTITY SHIPPED</th>
        <th>FOOTAGE QUANTITY</th>
        <th>SHIP DATE</th>
        <th>CREATED AT</th>
      </tr>

      <?php foreach ($inventory_items as $inventory) : ?>
        <tr>
          <td><?= $inventory['order_number']; ?></td>
          <td><?= $inventory['unit_number']?></td>
          <td><?= $inventory['ficha']?></td>
          <td><?= $inventory['description']?></td>
          <td><?= $inventory['quantity_shipped']?></td>
          <td><?= $inventory['footage_quantity']?></td>
          <td><?= $inventory['ship_date']?></td>
          <td><?= $inventory['created_at']?></td>
        </tr>
      <?php endforeach ?>

      <?php if (empty($inventory_items)) : ?>
        <tr><td colspan="8">No inventory items found.</td></tr>
      <?php endif; ?>
    </table>
  </main>
</body>
</html>