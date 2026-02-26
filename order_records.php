<?php
  session_start();
  require_once "db.php";
  require_once "functions/auth.php";
  require "functions/wms.php";
  require_login();
  $page = "order_records";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Records</title>
  <link rel="stylesheet" href="styles/normalize.css">
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
  <?php 
    include "components/header.php";
    include "components/nav.php";
    $order_records = get_orders();
  ?>
  <main>
    <div id="title">
      <h2>Order Records</h2>
      <h3><?= count($order_records) ?> orders</h3>
    </div>
    <table>
      <tr>
        <th>ORDER NUMBER</th>
        <th>COMPANY</th>
        <th>SHIPPING ADDRESS</th>
        <th>STATUS</th>
        <th>ITEMS</th>
        <th>ACTIONS</th>
      </tr>

      <?php foreach ($order_records as $order) : ?>
        <tr>
          <td><?= $order['order_number']; ?></td>
          <td><?= $order['ship_to_company']?></td>
            <td>
              <?= $order['ship_to_street']?>,
              <?= $order['ship_to_city']?>,
              <?= $order['ship_to_state']?>,
              <?= $order['ship_to_zip']?>
            </td>          
          <td><?= $order['status']?></td>
          <td>-</td>
          <td>-</td>
        </tr>
      <?php endforeach ?>

      <?php if (empty($order_records)) : ?>
        <tr><td colspan="6">No order records found.</td></tr>
      <?php endif; ?>
    </table>
  </main>
</body>
</html>