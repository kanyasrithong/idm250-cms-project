<?php
  session_start();
  require_once "db.php";
  require_once "functions/auth.php";
  require "functions/wms.php";
  require_login();
  $page = "mpl_records";

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_mpl'])) {

    $reference_number = $_POST['reference_number'] ?? null;
    confirm_mpl($reference_number);

    header("Location: mpl_records.php");
    exit;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MPL Records</title>
  <link rel="stylesheet" href="styles/normalize.css">
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
  <?php 
    include "components/header.php";
    include "components/nav.php";
    $mpl_records = get_mpls();
  ?>
  <main>
    <div id="title">
      <h2>MPL Records</h2>
      <h3><?= count($mpl_records) ?> MPLs</h3>
    </div>
    <table>
      <tr>
        <th>REFERENCE NUMBER</th>
        <th>TRAILER NUMBER</th>
        <th>EXPECTED ARRIVAL</th>
        <th>STATUS</th>
        <th>ACTIONS</th>
      </tr>

      <?php foreach ($mpl_records as $mpl) { ?>
        <tr>
          <td><?= $mpl['reference_number'] ?></td>
          <td><?= $mpl['trailer_number'] ?></td>
          <td><?= $mpl['expected_arrival'] ?></td>
          <td><?= $mpl['status'] ?></td>
          <td>
            <button type="button" class="btn-small view-button" data-modal-id="mpl-modal-<?= $mpl['id'] ?>">
              VIEW
            </button>
            <?php if (strtolower($mpl['status']) === 'open') { ?>
              <form class="callback-form" method="POST">
                <input type="hidden" name="reference_number" value="<?= htmlspecialchars($mpl['reference_number']) ?>">
                <button type="submit" name="confirm_mpl" class="btn-small">
                  CONFIRM
                </button>
              </form>
            <?php } ?>
          </td>
        </tr>
      <?php } ?>

      <?php if (empty($mpl_records)) { ?>
        <tr><td colspan="6">No MPLs found.</td></tr>
      <?php } ?>
    </table>
    <?php foreach ($mpl_records as $mpl) { ?>
      <?php $mpl_items = get_mpl_items($mpl['id']); ?>
      <div class="modal" id="mpl-modal-<?= $mpl['id'] ?>">
        <div class="modal-content">
          <button type="button" class="modal-close" data-modal-id="mpl-modal-<?= $mpl['id'] ?>">X</button>
          <h2>MPL ITEMS</h2>
          <p><strong>Reference Number:</strong> <?= htmlspecialchars($mpl['reference_number']) ?></p>
          <?php if (empty($mpl_items)) { ?>
            <p>No items found for this MPL.</p>
          <?php } else { ?>
            <table>
              <tr>
                <th>UNIT NUMBER</th>
                <th>SKU</th>
                <th>DESCRIPTION</th>
              </tr>
              <?php foreach ($mpl_items as $item) { ?>
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