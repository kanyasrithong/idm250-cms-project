<?php
  session_start();
  require_once "db.php";
  require_once "functions/auth.php";
  require "functions/wms.php";
  require_login();
  $page = "mpl_records";
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
        <th>ITEMS</th>
        <th>ACTIONS</th>
      </tr>

      <?php foreach ($mpl_records as $mpl) : ?>
        <tr>
          <td><?= $mpl['reference_number'] ?></td>
          <td><?= $mpl['trailer_number'] ?></td>
          <td><?= $mpl['expected_arrival'] ?></td>
          <td><?= $mpl['status'] ?></td>
          <td>-</td>
          <td>-</td>
        </tr>
      <?php endforeach ?>

      <?php if (empty($mpl_records)) : ?>
        <tr><td colspan="6">No MPLs found.</td></tr>
      <?php endif; ?>
    </table>
  </main>
</body>
</html>