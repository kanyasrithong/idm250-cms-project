<nav>
  <a <?php echo ($page === 'home') ? "class='nav-button active'" : "class='nav-button'"?> href="index.php">
    DASHBOARD
  </a>
  <a <?php echo ($page === 'sku_management') ? "class='nav-button active'" : "class='nav-button'"?> href="sku_management.php">SKU MANAGEMENT</a>
  <a <?php echo ($page === 'internal_inventory') ? "class='nav-button active'" : "class='nav-button'"?> href="internal_inventory.php">INVENTORY</a>
  <a <?php echo ($page === 'mpl_records') ? "class='nav-button active'" : "class='nav-button'"?> href="mpl_records.php">MPL RECORDS</a>
  <a <?php echo ($page === 'order_records') ? "class='nav-button active'" : "class='nav-button'"?> href="order_records.php">ORDER RECORDS</a>
</nav>