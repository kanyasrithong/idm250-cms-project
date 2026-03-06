<?php
  session_start();
  require_once "db.php";
  require_once "functions/auth.php";
  require "functions/wms.php";
  require_login();
  $page = "order_records";

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {

    $order_number = $_POST['order_number'];
    ship_order($order_number);

    header("Location: order_records.php");
    exit;
  }
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
    $shipped_orders = get_shipped_items();
  ?>
  <main>
    <div id="title">
      <h2>Order Records</h2>
      <h3><?= count($order_records) ?> Orders</h3>
    </div>
    <table>
      <tr>
        <th>ORDER NUMBER</th>
        <th>COMPANY</th>
        <th>SHIPPING ADDRESS</th>
        <th>STATUS</th>
        <th>ITEMS (UNIT NUMBER)</th>
        <th>ACTIONS</th>
      </tr>

      <?php foreach ($order_records as $order) { ?>
        <?php $order_items = get_order_items($order['id']); ?>
        <tr>
          <td><?= $order['order_number']; ?></td>
          <td><?= $order['ship_to_company']?></td>
            <td>
              <?= $order['ship_to_street']?>,
              <?= $order['ship_to_city']?>,
              <?= $order['ship_to_state']?>,
              <?= $order['ship_to_zip']?>
            </td>          
          <td><?= strtolower($order['status']) === 'open' ? 'RECEIVED' : 'SHIPPED' ?></td>
          <td>-</td>
          <td>
            <button type="button"
              class="btn-small view-button"
              data-modal-id="order-modal-<?= $order['id'] ?>">
              VIEW
            </button>
            <?php if (strtolower($order['status']) === 'open') { ?>
              <form class="callback-form" method="POST">
                <input type="hidden" name="order_number" value="<?= htmlspecialchars($order['order_number']) ?>">
                <button type="submit" name="confirm_order" class="btn-small">
                  SHIP
                </button>
              </form>
            <?php } ?>
          </td>
        </tr>
      <?php } ?>

      <?php if (empty($order_records)) { ?>
        <tr><td colspan="6">No order records found.</td></tr>
      <?php } ?>
    </table>
    <div id="title">
      <h2>Shipped Orders</h2>
      <h3><?= count($shipped_orders) ?> Shipped Orders</h3>
    </div>
    <table>
      <tr>
        <th>ORDER NUMBER</th>
        <th>COMPANY</th>
        <th>SHIPPED AT</th>
        <th>ITEM COUNT</th>
      </tr>
      <?php foreach ($shipped_orders as $order) { ?>
        <tr>
          <td><?= $order['order_number'] ?></td>
          <td><?= $order['ship_to_company'] ?></td>
          <td><?= $order['shipped_at'] ?></td>
          <td><?= $order['item_count'] ?></td>
        </tr>
      <?php } ?>
      <?php if (empty($shipped_orders)) { ?>
        <tr><td colspan="4">No shipped orders found.</td></tr>
      <?php } ?>
    </table>
    <?php foreach ($order_records as $order) { ?>
      <?php $order_items = get_order_items($order['id']); ?>
      <div class="modal" id="order-modal-<?= $order['id'] ?>">
        <div class="modal-content">
          <button
            type="button"
            class="modal-close"
            data-modal-id="order-modal-<?= $order['id'] ?>">
            X
          </button>
          <h2>ORDER ITEMS</h2>
          <p>
            <strong>Order Number:</strong>
            <?= htmlspecialchars($order['order_number']) ?>
          </p>
          <?php if (empty($order_items)) { ?>
            <p>No items found for this order.</p>
          <?php } else { ?>
            <table>
              <tr>
                <th>UNIT NUMBER</th>
                <th>SKU</th>
                <th>DESCRIPTION</th>
              </tr>
              <?php foreach ($order_items as $item) { ?>
                <tr>
                  <td><?= htmlspecialchars($item['unit_number']) ?></td>
                  <td><?= htmlspecialchars($item['sku']) ?></td>
                  <td><?= htmlspecialchars($item['description']) ?></td>
                </tr>
              <?php } ?>
            </table>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
  </main>
  <script>
    // Open modal
    document.querySelectorAll('.view-button').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-modal-id');
        document.getElementById(id).style.display = 'block';
      });
    });
    // Close modal (x button)
    document.querySelectorAll('.modal-close').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-modal-id');
        document.getElementById(id).style.display = 'none';
      });
    });
    // Close modal (clicking out of modal)
    document.querySelectorAll('.modal').forEach(modal => {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
      });
    });
</script>
</body>
</html>