<?php
  require_once "db.php";
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
    include "components/nav.php"
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
        <th>DIMENSIONS (L x W x H)</th>
        <th>WEIGHT</th>
        <th>ACTIONS</th>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>
  </main>
</body>
</html>