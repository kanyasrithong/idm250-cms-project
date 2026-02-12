<?php
  require_once "db.php";
  require "functions/wms.php";
  $page = "sku_management";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SKU Management</title>
  <link rel="stylesheet" href="styles/normalize.css">
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
  <?php 
    include "components/header.php";
    include "components/nav.php";
    $skus = get_skus();
  ?>
  <main>
    <div id="title">
      <h2>SKU Management</h2>
      <button>+ ADD SKU</button>
    </div>
    <table>
      <tr>
        <th>SKU</th>
        <th>DESCRIPTION</th>
        <th>UOM</th>
        <th>PIECES</th>
        <th>DIMENSIONS</th>
        <th>WEIGHT</th>
      </tr>
      
      <?php foreach ($skus as $sku) : ?>
        <tr>
          <td><?= $sku['sku']; ?></td>
          <td><?= $sku['description']?></td>
          <td><?= $sku['uom_primary']?></td>
          <td><?= $sku['piece_count']?></td>
          <td><?= $sku['length_inches']?>in x <?= $sku['width_inches']?>in x <?= $sku['height_inches']?>in</td>
          <td><?= $sku['weight_lbs']?>lbs</td>
        </tr>
      <?php endforeach ?>
    </table>
  </main>
</body>
</html>