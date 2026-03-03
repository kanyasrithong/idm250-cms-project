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
        <th>ITEMS (UNIT NUMBER)</th>
        <th>ACTIONS</th>
      </tr>

      <?php foreach ($mpl_records as $mpl) : ?>
        <?php $mpl_items = get_mpl_items($mpl['id']); ?>
        <tr>
          <td><?= $mpl['reference_number'] ?></td>
          <td><?= $mpl['trailer_number'] ?></td>
          <td><?= $mpl['expected_arrival'] ?></td>
          <td><?= $mpl['status'] ?></td>
          <td>
            <?php foreach ($mpl_items as $mpl_item) : ?>
              <p><?= $mpl_item['unit_number'] ?></p>
            <?php endforeach ?>
          </td>
          <td>
            <?php if (isset($reference_number)) : ?>
              <form class="callback-form" method="POST">
                <input type="hidden" name="reference_number" value="<?= htmlspecialchars($mpl['reference_number']) ?>">
                <button type="submit" name="confirm_mpl">
                  Confirm
                </button>
              </form>
            <?php endif ?>
          </td>
        </tr>
      <?php endforeach ?>

      <?php if (empty($mpl_records)) : ?>
        <tr><td colspan="6">No MPLs found.</td></tr>
      <?php endif; ?>
    </table>
  </main>
</body>
</html>