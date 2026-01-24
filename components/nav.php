<nav>
  <a <?php echo ($page === 'home') ? "class='nav-button active'" : "class='nav-button'"?> href="index.php">
    DASHBOARD
  </a>
  <a <?php echo ($page === 'sku') ? "class='nav-button active'" : "class='nav-button'"?> href="sku.php">SKU MANAGEMENT</a>
  <a <?php echo ($page === 'inventory') ? "class='nav-button active'" : "class='nav-button'"?> href="">INTERNAL INVENTORY</a>
  <a <?php echo ($page === 'mpl') ? "class='nav-button active'" : "class='nav-button'"?> href="">MPL RECORDS</a>
  <a <?php echo ($page === 'order') ? "class='nav-button active'" : "class='nav-button'"?> href="">ORDER RECORDS</a>
</nav>