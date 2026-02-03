<?php
  require_once "db.php";
  $page = "inventory";
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
    include "components/nav.php"
  ?>
  <main>
    <div id="title">
      <h2>Inventory</h2>
      <h3>0 units</h3>
    </div>
    <table>
      <tr>
        <th>UNIT ID</th>
        <th>SKU</th>
        <th>DESCRIPTION</th>
        <th>UOM</th>
        <th>RECEIVED</th>
      </tr>
      <tr>
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