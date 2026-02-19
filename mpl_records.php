<?php
  session_start();
  require_once "db.php";
  require_once "functions/auth.php";
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
    include "components/nav.php"
  ?>
  <main>
    <div id="title">
      <h2>MPL Records</h2>
      <h3>0 units</h3>
    </div>
    <table>
      <tr>
        <th>SKU</th>
        <th>REFERENCE NUMBER</th>
        <th>TRAILER NUMBER</th>
        <th>EXPECTED ARRIVAL</th>
        <th>ITEMS</th>
        <th>STATUS</th>
        <th>RECEIVED</th>
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